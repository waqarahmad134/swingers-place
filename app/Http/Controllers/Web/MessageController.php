<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display the conversation with the given user.
     */
    public function show(Request $request, User $user): View
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

        return view('pages.messages.show', [
            'otherUser' => $user,
            'messages' => $messages,
            'lastMessageId' => $messages->last()?->id ?? 0,
        ]);
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 422, 'You cannot message yourself.');

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        /** @var \App\Models\Message $message */
        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'body' => $validated['body'],
        ]);

        $payload = [
            'message' => $this->transformMessage($message, $currentUser->id),
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return redirect()
            ->route('messages.show', $user)
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
        $allMessages = Message::where(function ($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                      ->orWhere('receiver_id', $currentUser->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by conversation partner and get the latest message
        $conversationsMap = [];
        foreach ($allMessages as $message) {
            $otherUserId = $message->sender_id === $currentUser->id 
                ? $message->receiver_id 
                : $message->sender_id;
            
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
            $otherUser = $otherUserId === $conv['latest_message']->sender_id
                ? $conv['latest_message']->sender
                : $conv['latest_message']->receiver;

            if (!$otherUser || !$otherUser->is_active) {
                continue;
            }

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

