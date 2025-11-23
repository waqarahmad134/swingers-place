@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name . ' - ' . config('app.name'))

@section('full-width')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mx-auto max-w-4xl space-y-6">
            <div class="flex flex-col gap-2">
                <a href="{{ route('user.profile', $otherUser->id) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to {{ $otherUser->name }}'s profile
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-dark dark:text-white">Chat with {{ $otherUser->name }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Send direct messages and get replies instantly.</p>
                </div>
            </div>

            <div
                data-chat
                data-chat-send-url="{{ route('messages.store', $otherUser, false) }}"
                data-chat-poll-url="{{ route('messages.poll', $otherUser, false) }}"
                data-chat-last-id="{{ $lastMessageId }}"
                class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 flex h-96 flex-col"
            >
                <div data-chat-messages class="flex-1 min-h-0 space-y-4 overflow-y-auto bg-gray-50/70 p-4 pr-3 dark:bg-gray-950/40">
                    <div data-chat-empty class="flex h-full flex-col items-center justify-center gap-3 text-center text-gray-500 dark:text-gray-400 {{ $messages->isEmpty() ? '' : 'hidden' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m-2 8a9 9 0 110-18 9 9 0 010 18z" />
                        </svg>
                        <div>
                            <p class="text-base font-semibold">No messages yet</p>
                            <p class="text-sm">Say hello and start the conversation!</p>
                        </div>
                    </div>

                    @foreach ($messages as $message)
                        @php $isMe = $message->sender_id === auth()->id(); @endphp
                        <div class="flex flex-col gap-1 {{ $isMe ? 'items-end text-right' : 'items-start text-left' }}" data-chat-message data-chat-message-id="{{ $message->id }}">
                            <div class="max-w-[85%] rounded-2xl px-4 py-2 text-sm shadow-sm {{ $isMe ? 'bg-primary text-white rounded-br-md' : 'bg-white border border-gray-200 rounded-bl-md dark:bg-gray-800 dark:border-gray-700' }}">
                                {{ $message->body }}
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M j, g:i A') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <form data-chat-form action="{{ route('messages.store', $otherUser) }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        @csrf
                        <div class="flex-1">
                            <label for="chat-message" class="sr-only">Message</label>
                            <textarea
                                id="chat-message"
                                name="body"
                                rows="2"
                                data-chat-input
                                class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                placeholder="Write your message..."
                                maxlength="2000"
                                required
                            ></textarea>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Press Enter to send, Shift + Enter for a new line.</p>
                        </div>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-primary px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/50"
                            data-chat-submit
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11h6m0 0l-3-3m3 3l-3 3m-2-9h8a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

