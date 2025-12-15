@extends('layouts.app')

@section('title', 'Messages - ' . config('app.name'))

@section('full-width')
<div class="flex h-[calc(100vh-68px)] gap-3 dark:bg-gray-900 overflow-hidden m-10">
    <!-- Left Sidebar: Compact Conversation List -->
    <div class="w-72 border-r border-gray-200 rounded-3xl dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-col flex-shrink-0 overflow-hidden" style="box-shadow: 2px 2px 9px 0 rgba(0, 0, 0, 0.2);">
        <!-- Header -->
        <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ url()->previous() }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div class="flex items-center gap-2 flex-1">
                <i class="ri-message-3-line text-xl text-[#9810FA]"></i>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Messages</h2>
            </div>
            @php
                $totalUnread = collect($conversations)->sum('unread_count');
            @endphp
            @if($totalUnread > 0)
                <span class="bg-[#9810FA] text-white text-xs font-semibold rounded-full w-6 h-6 flex items-center justify-center">
                    {{ $totalUnread > 9 ? '9+' : $totalUnread }}
                </span>
            @endif
        </div>

        <!-- Search -->
        <div class="px-3 py-2">
            <div class="relative">
                <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <input 
                    type="text" 
                    id="search-conversations"
                    placeholder="Search conversations..." 
                    class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Conversations List -->
        <div class="flex-1 overflow-y-auto" id="conversations-list">
            @forelse($conversations as $conversation)
                @if($conversation['user_id'] == $currentUserId)
                    @continue
                @endif
                <a 
                    href="{{ route('messages.index', ['user' => $conversation['user_id']]) }}" 
                    class="conversation-item flex items-start gap-3 px-3 py-3 border-b-2 border-gray-50 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $selectedUser && $selectedUser->id == $conversation['user_id'] ? 'bg-purple-50 dark:bg-purple-900/20' : '' }}"
                    data-conversation-id="{{ $conversation['user_id'] }}"
                >
                    <!-- Avatar -->
                    <div class="relative flex-shrink-0">
                        @if($conversation['user_avatar'])
                            <img 
                                src="{{ $conversation['user_avatar'] }}" 
                                alt="{{ $conversation['user_name'] }}" 
                                class="w-10 h-10 rounded-full object-cover"
                            >
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold text-sm">
                                {{ $conversation['user_initials'] }}
                            </div>
                        @endif
                        @if($conversation['is_online'])
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <!-- Name & Time -->
                        <div class="flex items-baseline justify-between gap-2 mb-0.5">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                {{ $conversation['user_name'] }}
                            </h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
                                {{ $conversation['latest_message']['time_for_humans'] }}
                            </span>
                        </div>

                        <!-- Message Preview & Badge -->
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs text-gray-600 dark:text-gray-400 truncate flex-1">
                                {{ $conversation['latest_message']['body'] }}
                            </p>
                            @if($conversation['unread_count'] > 0)
                                <span class="bg-[#9810FA] text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0">
                                    {{ $conversation['unread_count'] > 9 ? '9+' : $conversation['unread_count'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Category Badge -->
                        <div class="mt-1">
                            @if($conversation['profile_type'] === 'business')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium inline-flex items-center gap-1">
                                    @if($conversation['is_online'])
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    @endif
                                    Business
                                </span>
                            @elseif($conversation['category'])
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium inline-flex items-center gap-1">
                                    @if($conversation['is_online'])
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    @endif
                                    {{ $conversation['category'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center px-4 py-10">
                    <i class="ri-message-3-line text-4xl text-gray-400 mb-3"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No conversations yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Panel: Chat Area (Full Space) -->
    <div class="flex-1 flex flex-col bg-white rounded-3xl dark:bg-gray-800 min-w-0 overflow-hidden" style="box-shadow: 2px 2px 9px 0 rgba(0, 0, 0, 0.2);">
        @if($selectedUser)
            <!-- Chat Header -->
            <div class="flex items-center justify-between px-6 py-3 rounded-t-3xl border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex-shrink-0">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <!-- Avatar -->
                    <div class="relative flex-shrink-0">
                        @if($selectedUser->profile_image)
                            <img 
                                src="{{ asset('storage/' . $selectedUser->profile_image) }}" 
                                alt="{{ $selectedUser->name }}" 
                                class="w-10 h-10 rounded-full object-cover"
                            >
                        @else
                            @php
                                $initials = '';
                                if ($selectedUser->first_name || $selectedUser->last_name) {
                                    $initials = strtoupper(substr($selectedUser->first_name ?? '', 0, 1) . substr($selectedUser->last_name ?? '', 0, 1));
                                } else {
                                    $nameParts = explode(' ', $selectedUser->name);
                                    $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr($nameParts[1] ?? '', 0, 1));
                                }
                            @endphp
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold text-sm">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $selectedUser->name }}</h3>
                        @php
                            $profile = $selectedUser->profile;
                            $isOnline = $selectedUser->isOnline() && ($profile?->show_online_status !== false);
                        @endphp
                        <p class="text-xs {{ $isOnline ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ $isOnline ? 'Online' : 'Offline' }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button class="p-2 text-[#9810FA] hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Video Call">
                        <i class="ri-vidicon-line text-xl"></i>
                    </button>
                    <button class="p-2 text-[#9810FA] hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Voice Call">
                        <i class="ri-phone-line text-xl"></i>
                    </button>
                    
                    <!-- More Options Dropdown -->
                    <div class="relative">
                        <button id="moreOptionsBtn" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors" title="More Options">
                            <i class="ri-more-2-fill text-xl"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="moreOptionsMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            <!-- Search Conversation -->
                            <button onclick="searchInConversation()" class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-3">
                                <i class="ri-search-line text-lg"></i>
                                <span>Search conversation</span>
                            </button>
                            
                            <!-- Clear Chat -->
                            <button onclick="clearChat()" class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-3">
                                <i class="ri-delete-bin-line text-lg"></i>
                                <span>Clear chat</span>
                            </button>
                            
                            <!-- Block User -->
                            <button onclick="blockUser()" class="w-full px-4 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                                <i class="ri-user-forbid-line text-lg"></i>
                                <span>Block this user</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search in Conversation -->
            <div id="conversationSearch" class="hidden border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3">
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input 
                        type="text" 
                        id="messageSearchInput"
                        placeholder="Search in messages..." 
                        class="w-full pl-10 pr-10 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                    <button onclick="closeConversationSearch()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Container Wrapper -->
            <div 
                data-chat
                data-chat-send-url="{{ route('messages.store', $selectedUser, false) }}"
                data-chat-poll-url="{{ route('messages.poll', $selectedUser, false) }}"
                data-chat-last-id="{{ $lastMessageId }}"
                data-user-id="{{ $selectedUser->id }}"
                class="flex-1 flex flex-col overflow-hidden"
            >
                <!-- Messages Area -->
                <div 
                    class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6 space-y-3"
                    id="chat-messages"
                >
                <!-- Empty State -->
                <div data-chat-empty class="flex h-full flex-col items-center justify-center gap-3 text-center text-gray-500 dark:text-gray-400 {{ $messages->isEmpty() ? '' : 'hidden' }}">
                    <i class="ri-message-3-line text-5xl"></i>
                    <div>
                        <p class="text-base font-semibold">No messages yet</p>
                        <p class="text-sm">Say hello and start the conversation!</p>
                    </div>
                </div>

                <!-- Messages -->
                @foreach ($messages as $message)
                    @php $isMe = $message->sender_id === auth()->id(); @endphp
                    <div class="flex flex-col gap-0.5 {{ $isMe ? 'items-end' : 'items-start' }}" data-chat-message data-chat-message-id="{{ $message->id }}">
                        <div class="max-w-[65%] rounded-2xl px-4 py-2.5 text-sm break-words {{ $isMe ? 'bg-gradient-to-r from-[#9810FA] to-[#E60076] text-white rounded-br-sm' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm rounded-bl-sm' }}">
                            @if($message->attachment && $message->attachment_type === 'image')
                                <img src="{{ asset('storage/' . $message->attachment) }}" alt="Image" class="max-w-full rounded-lg mb-2 max-h-64 object-cover">
                            @endif
                            
                            @if($message->attachment && $message->attachment_type === 'file')
                                <a href="{{ asset('storage/' . $message->attachment) }}" download="{{ $message->attachment_name }}" class="flex items-center gap-2 p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors mb-2">
                                    <i class="ri-file-line text-2xl"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate">{{ $message->attachment_name }}</p>
                                        <p class="text-xs opacity-75">Click to download</p>
                                    </div>
                                    <i class="ri-download-line text-lg"></i>
                                </a>
                            @endif
                            
                            @if($message->body)
                                {{ $message->body }}
                            @endif
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 px-1 mt-0.5">{{ $message->created_at->format('g:i A') }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Message Input Area -->
            <div class="border-t rounded-b-3xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 flex-shrink-0">
                <!-- File Preview Area -->
                <div id="filePreviewArea" class="hidden mb-3 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="ri-file-line text-2xl text-gray-600 dark:text-gray-400"></i>
                            <div>
                                <p id="fileName" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                                <p id="fileSize" class="text-xs text-gray-500 dark:text-gray-400"></p>
                            </div>
                        </div>
                        <button type="button" onclick="removeAttachment()" class="text-red-500 hover:text-red-700">
                            <i class="ri-close-circle-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Image Preview Area -->
                <div id="imagePreviewArea" class="hidden mb-3 p-2 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="relative inline-block">
                        <img id="imagePreview" src="" alt="Preview" class="max-h-32 rounded-lg">
                        <button type="button" onclick="removeImageAttachment()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                            <i class="ri-close-line text-sm"></i>
                        </button>
                    </div>
                </div>

                <form 
                    data-chat-form 
                    action="javascript:void(0);" 
                    method="POST"
                    enctype="multipart/form-data"
                    class="flex items-center gap-3"
                >
                    @csrf
                    <!-- Hidden File Inputs -->
                    <input type="file" id="fileAttachment" name="attachment" class="hidden" accept=".pdf,.doc,.docx,.txt,.zip,.rar">
                    <input type="file" id="imageAttachment" name="image" class="hidden" accept="image/*">
                    
                    <!-- Left Action Buttons -->
                    <button type="button" onclick="document.getElementById('fileAttachment').click()" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors flex-shrink-0" title="Attach File">
                        <i class="ri-attachment-2 text-xl"></i>
                    </button>
                    <button type="button" onclick="document.getElementById('imageAttachment').click()" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors flex-shrink-0" title="Add Photo">
                        <i class="ri-image-line text-xl"></i>
                    </button>
                    
                    <!-- Emoji Picker Button -->
                    <div class="relative">
                        <button type="button" id="emojiPickerBtn" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors flex-shrink-0" title="Add Emoji">
                            <i class="ri-emotion-happy-line text-xl"></i>
                        </button>
                        
                        <!-- Emoji Picker Dropdown -->
                        <div id="emojiPicker" class="hidden absolute bottom-12 left-0 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-3 z-50" style="width: 320px; max-height: 300px; overflow-y: auto;">
                            <div class="grid grid-cols-8 gap-2">
                                <button type="button" onclick="insertEmoji('😀')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😀</button>
                                <button type="button" onclick="insertEmoji('😃')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😃</button>
                                <button type="button" onclick="insertEmoji('😄')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😄</button>
                                <button type="button" onclick="insertEmoji('😁')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😁</button>
                                <button type="button" onclick="insertEmoji('😆')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😆</button>
                                <button type="button" onclick="insertEmoji('😅')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😅</button>
                                <button type="button" onclick="insertEmoji('😂')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😂</button>
                                <button type="button" onclick="insertEmoji('🤣')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤣</button>
                                <button type="button" onclick="insertEmoji('😊')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😊</button>
                                <button type="button" onclick="insertEmoji('😇')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😇</button>
                                <button type="button" onclick="insertEmoji('🙂')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🙂</button>
                                <button type="button" onclick="insertEmoji('🙃')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🙃</button>
                                <button type="button" onclick="insertEmoji('😉')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😉</button>
                                <button type="button" onclick="insertEmoji('😌')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😌</button>
                                <button type="button" onclick="insertEmoji('😍')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😍</button>
                                <button type="button" onclick="insertEmoji('🥰')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🥰</button>
                                <button type="button" onclick="insertEmoji('😘')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😘</button>
                                <button type="button" onclick="insertEmoji('😗')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😗</button>
                                <button type="button" onclick="insertEmoji('😙')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😙</button>
                                <button type="button" onclick="insertEmoji('😚')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😚</button>
                                <button type="button" onclick="insertEmoji('😋')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😋</button>
                                <button type="button" onclick="insertEmoji('😛')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😛</button>
                                <button type="button" onclick="insertEmoji('😝')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😝</button>
                                <button type="button" onclick="insertEmoji('😜')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😜</button>
                                <button type="button" onclick="insertEmoji('🤪')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤪</button>
                                <button type="button" onclick="insertEmoji('🤨')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤨</button>
                                <button type="button" onclick="insertEmoji('🧐')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🧐</button>
                                <button type="button" onclick="insertEmoji('🤓')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤓</button>
                                <button type="button" onclick="insertEmoji('😎')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😎</button>
                                <button type="button" onclick="insertEmoji('🤩')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤩</button>
                                <button type="button" onclick="insertEmoji('🥳')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🥳</button>
                                <button type="button" onclick="insertEmoji('😏')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😏</button>
                                <button type="button" onclick="insertEmoji('😔')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😔</button>
                                <button type="button" onclick="insertEmoji('😢')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😢</button>
                                <button type="button" onclick="insertEmoji('😭')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😭</button>
                                <button type="button" onclick="insertEmoji('😤')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😤</button>
                                <button type="button" onclick="insertEmoji('😠')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😠</button>
                                <button type="button" onclick="insertEmoji('😡')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">😡</button>
                                <button type="button" onclick="insertEmoji('❤️')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">❤️</button>
                                <button type="button" onclick="insertEmoji('💕')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💕</button>
                                <button type="button" onclick="insertEmoji('💖')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💖</button>
                                <button type="button" onclick="insertEmoji('💗')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💗</button>
                                <button type="button" onclick="insertEmoji('💙')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💙</button>
                                <button type="button" onclick="insertEmoji('💚')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💚</button>
                                <button type="button" onclick="insertEmoji('💛')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💛</button>
                                <button type="button" onclick="insertEmoji('🧡')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🧡</button>
                                <button type="button" onclick="insertEmoji('💜')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💜</button>
                                <button type="button" onclick="insertEmoji('🖤')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🖤</button>
                                <button type="button" onclick="insertEmoji('👍')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">👍</button>
                                <button type="button" onclick="insertEmoji('👎')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">👎</button>
                                <button type="button" onclick="insertEmoji('👏')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">👏</button>
                                <button type="button" onclick="insertEmoji('🙌')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🙌</button>
                                <button type="button" onclick="insertEmoji('👌')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">👌</button>
                                <button type="button" onclick="insertEmoji('✌️')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">✌️</button>
                                <button type="button" onclick="insertEmoji('🤞')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤞</button>
                                <button type="button" onclick="insertEmoji('🤟')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤟</button>
                                <button type="button" onclick="insertEmoji('🤘')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤘</button>
                                <button type="button" onclick="insertEmoji('🤙')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🤙</button>
                                <button type="button" onclick="insertEmoji('💪')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💪</button>
                                <button type="button" onclick="insertEmoji('🙏')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🙏</button>
                                <button type="button" onclick="insertEmoji('🎉')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🎉</button>
                                <button type="button" onclick="insertEmoji('🎊')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🎊</button>
                                <button type="button" onclick="insertEmoji('🎈')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🎈</button>
                                <button type="button" onclick="insertEmoji('🎁')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🎁</button>
                                <button type="button" onclick="insertEmoji('🔥')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">🔥</button>
                                <button type="button" onclick="insertEmoji('⭐')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">⭐</button>
                                <button type="button" onclick="insertEmoji('✨')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">✨</button>
                                <button type="button" onclick="insertEmoji('💫')" class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1">💫</button>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <input
                        type="text"
                        id="chat-message"
                        name="body"
                        data-chat-input
                        class="flex-1 px-4 py-2 text-sm text-gray-900 dark:text-white bg-transparent border-none focus:outline-none placeholder-gray-400 dark:placeholder-gray-500 min-w-0"
                        placeholder="Type a message..."
                        maxlength="2000"
                    />

                    <!-- Right Action Buttons -->
                    <button type="button" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors flex-shrink-0" title="Voice Message">
                        <i class="ri-mic-line text-xl"></i>
                    </button>
                    <button
                        type="button"
                        data-chat-submit
                        class="p-2.5 bg-[#9810FA] hover:bg-purple-700 text-white rounded-full transition-colors flex items-center justify-center flex-shrink-0"
                        title="Send Message"
                    >
                        <i class="ri-send-plane-fill text-lg"></i>
                    </button>
                </form>
            </div>
            </div>
        @else
            <!-- No Chat Selected -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <i class="ri-message-3-line text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">Select a conversation to start messaging</p>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Emoji Picker Toggle
    const emojiPickerBtn = document.getElementById('emojiPickerBtn');
    const emojiPicker = document.getElementById('emojiPicker');
    
    if (emojiPickerBtn && emojiPicker) {
        emojiPickerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            emojiPicker.classList.toggle('hidden');
        });
        
        // Close emoji picker when clicking outside
        document.addEventListener('click', function(e) {
            if (!emojiPickerBtn.contains(e.target) && !emojiPicker.contains(e.target)) {
                emojiPicker.classList.add('hidden');
            }
        });
    }
    
    // File Attachment Handler
    const fileAttachment = document.getElementById('fileAttachment');
    if (fileAttachment) {
        fileAttachment.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const filePreviewArea = document.getElementById('filePreviewArea');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePreviewArea.classList.remove('hidden');
            }
        });
    }
    
    // Image Attachment Handler
    const imageAttachment = document.getElementById('imageAttachment');
    if (imageAttachment) {
        imageAttachment.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreviewArea = document.getElementById('imagePreviewArea');
                    const imagePreview = document.getElementById('imagePreview');
                    
                    imagePreview.src = e.target.result;
                    imagePreviewArea.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // More Options Dropdown Toggle
    const moreOptionsBtn = document.getElementById('moreOptionsBtn');
    const moreOptionsMenu = document.getElementById('moreOptionsMenu');
    
    if (moreOptionsBtn && moreOptionsMenu) {
        moreOptionsBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            moreOptionsMenu.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!moreOptionsBtn.contains(e.target) && !moreOptionsMenu.contains(e.target)) {
                moreOptionsMenu.classList.add('hidden');
            }
        });
        
        // Close dropdown when clicking menu items
        moreOptionsMenu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function() {
                moreOptionsMenu.classList.add('hidden');
            });
        });
    }
    
    // Conversation search
    const searchInput = document.getElementById('search-conversations');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.conversation-item');
            
            items.forEach(item => {
                const name = item.querySelector('h3').textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Chat functionality
    const chatContainer = document.querySelector('[data-chat]');
    if (chatContainer) {
        const sendUrl = chatContainer.dataset.chatSendUrl;
        const pollUrl = chatContainer.dataset.chatPollUrl;
        const form = chatContainer.querySelector('[data-chat-form]');
        const input = chatContainer.querySelector('[data-chat-input]');
        const messagesContainer = document.getElementById('chat-messages');
        const emptyState = chatContainer.querySelector('[data-chat-empty]');
        let lastMessageId = parseInt(chatContainer.dataset.chatLastId) || 0;
        let pollingInterval = null;

        // Send message function
        const sendMessage = async function() {
            if (!input || !form || !sendUrl) {
                console.error('Missing required elements for sending message');
                return;
            }
            
            const body = input.value.trim();
            const fileInput = document.getElementById('fileAttachment');
            const imageInput = document.getElementById('imageAttachment');
            
            // Check if there's a message or attachment
            if (!body && (!fileInput || !fileInput.files.length) && (!imageInput || !imageInput.files.length)) {
                return;
            }

            // Disable input while sending
            input.disabled = true;
            const submitBtn = form.querySelector('[data-chat-submit]');
            if (submitBtn) submitBtn.disabled = true;

            try {
                // Create FormData for file uploads
                const formData = new FormData();
                formData.append('body', body || ' '); // Send space if only file
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    formData.append('_token', csrfToken.content);
                }
                
                if (fileInput && fileInput.files.length) {
                    formData.append('attachment', fileInput.files[0]);
                }
                
                if (imageInput && imageInput.files.length) {
                    formData.append('image', imageInput.files[0]);
                }

                const response = await fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.message) {
                        addMessage(data.message);
                        input.value = '';
                        lastMessageId = data.message.id;
                        
                        // Clear file inputs and previews
                        if (fileInput) {
                            fileInput.value = '';
                        }
                        if (imageInput) {
                            imageInput.value = '';
                        }
                        const filePreviewArea = document.getElementById('filePreviewArea');
                        const imagePreviewArea = document.getElementById('imagePreviewArea');
                        if (filePreviewArea) filePreviewArea.classList.add('hidden');
                        if (imagePreviewArea) imagePreviewArea.classList.add('hidden');
                        
                        if (emptyState) emptyState.classList.add('hidden');
                        
                        // Scroll to bottom after adding message
                        if (messagesContainer) {
                            setTimeout(() => {
                                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                            }, 100);
                        }
                    }
                } else {
                    // Handle error response
                    const errorData = await response.json().catch(() => ({}));
                    console.error('Failed to send message:', errorData);
                    if (window.showToast) {
                        window.showToast(errorData.message || 'Failed to send message', 'error');
                    } else {
                        alert(errorData.message || 'Failed to send message');
                    }
                }
            } catch (error) {
                console.error('Failed to send message:', error);
                if (window.showToast) {
                    window.showToast('Failed to send message. Please try again.', 'error');
                } else {
                    alert('Failed to send message. Please try again.');
                }
            } finally {
                input.disabled = false;
                const submitBtn = form.querySelector('[data-chat-submit]');
                if (submitBtn) submitBtn.disabled = false;
                input.focus();
            }
        };

        // Attach event listeners
        if (form && input) {
            console.log('Form and input found, attaching listeners');
            
            // Form submit (for Enter key)
            form.addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                sendMessage();
                return false;
            });
            
            // Submit button click - use direct selector
            const submitBtn = document.querySelector('[data-chat-submit]');
            console.log('Submit button found:', !!submitBtn);
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    console.log('Submit button clicked');
                    e.preventDefault();
                    e.stopPropagation();
                    sendMessage();
                });
            } else {
                console.error('Submit button not found');
            }
            
            // Enter key in input
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    console.log('Enter key pressed in input');
                    e.preventDefault();
                    e.stopPropagation();
                    sendMessage();
                }
            });
            
            console.log('All event listeners attached successfully');
        } else {
            console.error('Form or input not found', { form: !!form, input: !!input });
        }

        // Poll for new messages
        function startPolling() {
            if (pollingInterval) return;
            
            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch(`${pollUrl}?after=${lastMessageId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                addMessage(msg);
                                lastMessageId = Math.max(lastMessageId, msg.id);
                            });
                            if (emptyState) emptyState.classList.add('hidden');
                        }
                    }
                } catch (error) {
                    console.error('Failed to poll messages:', error);
                }
            }, 3000);
        }

        function addMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex flex-col gap-0.5 ${message.is_me ? 'items-end' : 'items-start'}`;
            messageDiv.setAttribute('data-chat-message', '');
            messageDiv.setAttribute('data-chat-message-id', message.id);

            const bubbleDiv = document.createElement('div');
            bubbleDiv.className = `max-w-[65%] rounded-2xl px-4 py-2.5 text-sm break-words ${message.is_me ? 'bg-gradient-to-r from-[#9810FA] to-[#E60076] text-white rounded-br-sm' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm rounded-bl-sm'}`;
            bubbleDiv.textContent = message.body;

            const timeSpan = document.createElement('span');
            timeSpan.className = 'text-xs text-gray-500 dark:text-gray-400 px-1 mt-0.5';
            timeSpan.textContent = message.time_for_humans;

            messageDiv.appendChild(bubbleDiv);
            messageDiv.appendChild(timeSpan);
            messagesContainer.appendChild(messageDiv);

            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Start polling when page loads
        if (pollUrl) {
            startPolling();
        }

        // Scroll to bottom on load
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }
});

// More Options Functions
function searchInConversation() {
    const searchBox = document.getElementById('conversationSearch');
    const searchInput = document.getElementById('messageSearchInput');
    
    if (searchBox && searchInput) {
        searchBox.classList.remove('hidden');
        searchInput.focus();
    }
}

function closeConversationSearch() {
    const searchBox = document.getElementById('conversationSearch');
    const searchInput = document.getElementById('messageSearchInput');
    
    if (searchBox && searchInput) {
        searchBox.classList.add('hidden');
        searchInput.value = '';
        
        // Show all messages again
        const messages = document.querySelectorAll('[data-chat-message]');
        messages.forEach(msg => msg.style.display = 'flex');
    }
}

// Search messages in real-time
document.addEventListener('DOMContentLoaded', function() {
    const messageSearchInput = document.getElementById('messageSearchInput');
    if (messageSearchInput) {
        messageSearchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const messages = document.querySelectorAll('[data-chat-message]');
            
            messages.forEach(msg => {
                const messageText = msg.querySelector('div').textContent.toLowerCase();
                if (messageText.includes(searchTerm)) {
                    msg.style.display = 'flex';
                } else {
                    msg.style.display = 'none';
                }
            });
        });
    }
});

async function clearChat() {
    if (!confirm('Are you sure you want to clear this chat? This action cannot be undone.')) {
        return;
    }
    
    const chatContainer = document.querySelector('[data-chat]');
    const userId = chatContainer ? chatContainer.dataset.userId : null;
    
    if (!userId) {
        alert('Error: Could not identify user.');
        return;
    }
    
    try {
        const response = await fetch(`/messages/${userId}/clear`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        
        if (response.ok) {
            // Remove all messages from UI
            const messages = document.querySelectorAll('[data-chat-message]');
            messages.forEach(msg => msg.remove());
            
            // Show empty state
            const emptyState = document.querySelector('[data-chat-empty]');
            if (emptyState) emptyState.classList.remove('hidden');
            
            alert('Chat cleared successfully!');
            
            // Reload page after 1 second
            setTimeout(() => window.location.reload(), 1000);
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to clear chat. Please try again.');
        }
    } catch (error) {
        console.error('Error clearing chat:', error);
        alert('An error occurred. Please try again.');
    }
}

async function blockUser() {
    if (!confirm('Are you sure you want to block this user? They will no longer be able to message you.')) {
        return;
    }
    
    const chatContainer = document.querySelector('[data-chat]');
    const userId = chatContainer ? chatContainer.dataset.userId : null;
    
    if (!userId) {
        alert('Error: Could not identify user.');
        return;
    }
    
    try {
        const response = await fetch(`/messages/${userId}/block`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        
        if (response.ok) {
            alert('User blocked successfully!');
            window.location.href = '/messages';
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to block user. Please try again.');
        }
    } catch (error) {
        console.error('Error blocking user:', error);
        alert('An error occurred. Please try again.');
    }
}

// Emoji Functions
function insertEmoji(emoji) {
    const input = document.getElementById('chat-message');
    if (input) {
        input.value += emoji;
        input.focus();
        
        // Close emoji picker
        document.getElementById('emojiPicker').classList.add('hidden');
    }
}

// File Attachment Functions
function removeAttachment() {
    const fileInput = document.getElementById('fileAttachment');
    const filePreviewArea = document.getElementById('filePreviewArea');
    
    if (fileInput) fileInput.value = '';
    if (filePreviewArea) filePreviewArea.classList.add('hidden');
}

function removeImageAttachment() {
    const imageInput = document.getElementById('imageAttachment');
    const imagePreviewArea = document.getElementById('imagePreviewArea');
    
    if (imageInput) imageInput.value = '';
    if (imagePreviewArea) imagePreviewArea.classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>
@endpush
@endsection
