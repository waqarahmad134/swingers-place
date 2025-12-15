<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageSetting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display all conversations with an optional selected conversation.
     */
    public function index(Request $request): View
    {
        $currentUser = $request->user();
        $selectedUserId = $request->get('user');

        // Get all conversations
        $conversations = $this->getAllConversations($currentUser);

        $selectedUser = null;
        $messages = collect();
        $lastMessageId = 0;

        // If a user is selected, load their conversation
        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);
            
            if ($selectedUser && $selectedUser->is_active && $selectedUser->id !== $currentUser->id) {
                $messages = Message::betweenUsers($currentUser->id, $selectedUser->id)
                    ->orderBy('created_at')
                    ->get();

                // Mark incoming messages as read
                Message::where('sender_id', $selectedUser->id)
                    ->where('receiver_id', $currentUser->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                $lastMessageId = $messages->last()?->id ?? 0;
            }
        }

        return view('pages.messages.index', [
            'conversations' => $conversations,
            'selectedUser' => $selectedUser,
            'messages' => $messages,
            'lastMessageId' => $lastMessageId,
            'currentUserId' => $currentUser->id,
        ]);
    }

    /**
     * Display the conversation with the given user.
     */
    public function show(Request $request, User $user): RedirectResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 403, 'You cannot message yourself.');

        $messages = Message::betweenUsers($currentUser->id, $user->id)
            ->orderBy('created_at')
            ->get();

        // Mark incoming messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Redirect to messages index with selected user
        return redirect()->route('messages.index', ['user' => $user->id]);
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 422, 'You cannot message yourself.');

        // Check global messaging setting first
        $messageSettings = MessageSetting::getSettings();
        if ($messageSettings->global_messaging_enabled === false) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Messaging is currently disabled for all users.'], 403);
            }
            return redirect()->back()->with('error', 'Messaging is currently disabled for all users.');
        }

        // Check if admin has blocked this user from messaging
        // Admins should always be able to send messages, so skip this check for admins
        if (!$currentUser->is_admin && $currentUser->can_message === false) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your messaging has been blocked by admin.'], 403);
            }
            return redirect()->back()->with('error', 'Your messaging has been blocked by admin.');
        }

        // Check if either user has blocked the other
        $isBlocked = \DB::table('blocked_users')
            ->where(function ($query) use ($currentUser, $user) {
                $query->where('user_id', $currentUser->id)
                      ->where('blocked_user_id', $user->id);
            })
            ->orWhere(function ($query) use ($currentUser, $user) {
                $query->where('user_id', $user->id)
                      ->where('blocked_user_id', $currentUser->id);
            })
            ->exists();

        if ($isBlocked) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Cannot send message.'], 403);
            }
            return redirect()->back()->with('error', 'Cannot send message to this user.');
        }

        try {
            $validated = $request->validate([
                'body' => ['nullable', 'string', 'max:2000'],
                'attachment' => ['nullable', 'file', 'max:10240'], // 10MB max
                'image' => ['nullable', 'image', 'max:5120'], // 5MB max
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        $attachmentPath = null;
        $attachmentType = null;
        $attachmentName = null;

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('attachments', 'public');
            $attachmentType = 'file';
        }

        // Handle image attachment
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('images', 'public');
            $attachmentType = 'image';
        }

        /** @var \App\Models\Message $message */
        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'body' => $validated['body'] ?? '',
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'attachment_name' => $attachmentName,
        ]);

        $payload = [
            'message' => $this->transformMessage($message, $currentUser->id),
        ];

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json($payload);
        }

        return redirect()
            ->route('messages.index', ['user' => $user->id])
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Poll for new messages after a specific message ID.
     */
    public function poll(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 403, 'Invalid conversation.');

        $afterId = max(0, (int) $request->integer('after', 0));

        $messages = Message::betweenUsers($currentUser->id, $user->id)
            ->when($afterId > 0, fn ($query) => $query->where('id', '>', $afterId))
            ->orderBy('id')
            ->get();

        $incomingIds = $messages
            ->where('receiver_id', $currentUser->id)
            ->pluck('id');

        if ($incomingIds->isNotEmpty()) {
            Message::whereIn('id', $incomingIds)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json([
            'messages' => $messages
                ->map(fn (Message $message) => $this->transformMessage($message, $currentUser->id))
                ->values(),
        ]);
    }

    /**
     * Get recent conversations with message previews.
     */
    public function recent(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        
        // Get all messages for the current user (as sender or receiver)
        // Exclude messages where sender and receiver are the same
        $allMessages = Message::where(function ($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                      ->orWhere('receiver_id', $currentUser->id);
            })
            ->whereColumn('sender_id', '!=', 'receiver_id') // Exclude self-messages
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by conversation partner and get the latest message
        $conversationsMap = [];
        foreach ($allMessages as $message) {
            $otherUserId = $message->sender_id === $currentUser->id 
                ? $message->receiver_id 
                : $message->sender_id;
            
            // Skip if somehow the other user is the current user
            if ($otherUserId === $currentUser->id) {
                continue;
            }
            
            if (!isset($conversationsMap[$otherUserId])) {
                $conversationsMap[$otherUserId] = [
                    'latest_message' => $message,
                    'latest_at' => $message->created_at,
                ];
            }
        }

        // Sort by latest message time and limit to 5
        usort($conversationsMap, function ($a, $b) {
            return $b['latest_at'] <=> $a['latest_at'];
        });
        $conversationsMap = array_slice($conversationsMap, 0, 5);

        $result = [];
        $totalUnread = 0;

        foreach ($conversationsMap as $otherUserId => $conv) {
            // Skip if this is the current user (shouldn't happen, but safety check)
            if ($otherUserId === $currentUser->id) {
                continue;
            }

            // Fetch the user directly by ID instead of relying on relationship
            // Use withTrashed() to include soft-deleted users so conversations still show
            $otherUser = User::withTrashed()->find($otherUserId);

            if (!$otherUser) {
                continue;
            }

            // Skip if user is soft-deleted
            if ($otherUser->trashed()) {
                continue;
            }

            // Show conversations even if user is inactive (for message history)

            $unreadCount = Message::where('sender_id', $otherUser->id)
                ->where('receiver_id', $currentUser->id)
                ->whereNull('read_at')
                ->count();

            $totalUnread += $unreadCount;
            $latestMessage = $conv['latest_message'];

            $result[] = [
                'user_id' => $otherUser->id,
                'user_name' => $otherUser->name,
                'user_avatar' => $otherUser->profile_image ? asset('storage/' . $otherUser->profile_image) : null,
                'latest_message' => [
                    'id' => $latestMessage->id,
                    'body' => \Str::limit($latestMessage->body, 50),
                    'is_me' => $latestMessage->sender_id === $currentUser->id,
                    'created_at' => $latestMessage->created_at->toIso8601String(),
                    'time_for_humans' => $latestMessage->created_at->format('M j, g:i A'),
                ],
                'unread_count' => $unreadCount,
            ];
        }

        return response()->json([
            'conversations' => $result,
            'unread_count' => $totalUnread,
        ]);
    }

    /**
     * Get all conversations for the current user.
     */
    protected function getAllConversations(User $currentUser): array
    {
        // Get distinct conversation partners using a subquery
        $conversationPartners = Message::selectRaw('
                CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END as other_user_id,
                MAX(created_at) as latest_message_at
            ', [$currentUser->id])
            ->where(function ($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                      ->orWhere('receiver_id', $currentUser->id);
            })
            ->whereColumn('sender_id', '!=', 'receiver_id')
            ->groupBy('other_user_id')
            ->orderBy('latest_message_at', 'desc')
            ->get();

        $result = [];

        foreach ($conversationPartners as $partner) {
            $otherUserId = $partner->other_user_id;
            
            // Skip if it's the current user
            if ($otherUserId == $currentUser->id) {
                continue;
            }

            // Get the other user
            $otherUser = User::withTrashed()->find($otherUserId);
            
            // Skip if user doesn't exist or is soft-deleted
            if (!$otherUser || $otherUser->trashed()) {
                continue;
            }

            // Get the latest message between these two users
            $latestMessage = Message::where(function ($query) use ($currentUser, $otherUserId) {
                $query->where(function ($q) use ($currentUser, $otherUserId) {
                    $q->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUserId);
                })->orWhere(function ($q) use ($currentUser, $otherUserId) {
                    $q->where('sender_id', $otherUserId)
                      ->where('receiver_id', $currentUser->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();

            if (!$latestMessage) {
                continue;
            }

            // Get unread count
            $unreadCount = Message::where('sender_id', $otherUserId)
                ->where('receiver_id', $currentUser->id)
                ->whereNull('read_at')
                ->count();

            // Get profile
            $profile = $otherUser->profile;
            $category = $profile?->category ?? '';

            // Format category
            $categoryDisplay = match($category) {
                'single_male' => 'Single Male',
                'single_female' => 'Single Female',
                'couple' => 'Couple',
                'couple_ff' => 'Couple F/F',
                'couple_mm' => 'Couple M/M',
                'group' => 'Group',
                'transgender' => 'Transgender',
                'non_binary' => 'Non-Binary',
                default => $category ? ucfirst(str_replace('_', ' ', $category)) : null,
            };

            // Get avatar initials
            $initials = '';
            if ($otherUser->first_name || $otherUser->last_name) {
                $initials = strtoupper(substr($otherUser->first_name ?? '', 0, 1) . substr($otherUser->last_name ?? '', 0, 1));
            } else {
                $nameParts = explode(' ', $otherUser->name);
                $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr($nameParts[1] ?? '', 0, 1));
            }

            // Format time
            $diffInMinutes = (int) $latestMessage->created_at->diffInMinutes(now());
            $diffInHours = (int) $latestMessage->created_at->diffInHours(now());
            $diffInDays = (int) $latestMessage->created_at->diffInDays(now());
            
            if ($diffInMinutes < 1) {
                $timeDisplay = 'Just now';
            } elseif ($diffInMinutes < 60) {
                $timeDisplay = $diffInMinutes . ' min ago';
            } elseif ($diffInHours < 24) {
                $timeDisplay = $diffInHours . ' hour' . ($diffInHours > 1 ? 's' : '') . ' ago';
            } elseif ($diffInDays == 1) {
                $timeDisplay = '1 day ago';
            } elseif ($diffInDays < 7) {
                $timeDisplay = $diffInDays . ' days ago';
            } elseif ($latestMessage->created_at->isCurrentYear()) {
                $timeDisplay = $latestMessage->created_at->format('M j');
            } else {
                $timeDisplay = $latestMessage->created_at->format('M j, Y');
            }

            $result[] = [
                'user_id' => $otherUser->id,
                'user_name' => $otherUser->name ?? 'Unknown User',
                'user_avatar' => $otherUser->profile_image ? asset('storage/' . $otherUser->profile_image) : null,
                'user_initials' => $initials,
                'category' => $categoryDisplay,
                'profile_type' => $profile?->profile_type ?? 'normal',
                'is_online' => $otherUser->isOnline() && ($profile?->show_online_status !== false),
                'latest_message' => [
                    'id' => $latestMessage->id,
                    'body' => \Str::limit($latestMessage->body ?? '', 50),
                    'is_me' => $latestMessage->sender_id === $currentUser->id,
                    'created_at' => $latestMessage->created_at->toIso8601String(),
                    'time_for_humans' => $timeDisplay,
                ],
                'unread_count' => $unreadCount,
            ];
        }

        return $result;
    }

    /**
     * Clear all messages in a conversation.
     */
    public function clearChat(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 403, 'Invalid action.');

        // Delete all messages between the two users
        Message::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $currentUser->id);
        })->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat cleared successfully.',
        ]);
    }

    /**
     * Block a user from messaging.
     */
    public function blockUser(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 403, 'You cannot block yourself.');

        // Create blocked_users table entry if it doesn't exist
        // For now, we'll use a simple approach - you can create a proper migration later
        try {
            \DB::table('blocked_users')->insert([
                'user_id' => $currentUser->id,
                'blocked_user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // If table doesn't exist, return error message
            return response()->json([
                'success' => false,
                'message' => 'Block feature requires database setup. Please contact administrator.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'User blocked successfully.',
        ]);
    }

    /**
     * Prepare a message payload for the frontend.
     */
    protected function transformMessage(Message $message, int $currentUserId): array
    {
        return [
            'id' => $message->id,
            'body' => $message->body,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'is_me' => $message->sender_id === $currentUserId,
            'created_at' => $message->created_at->toIso8601String(),
            'time_for_humans' => $message->created_at->format('M j, g:i A'),
        ];
    }
}

