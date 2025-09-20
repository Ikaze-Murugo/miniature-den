<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Messages') }}
            </h2>
            <a href="{{ route('properties.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200">
                Back to Properties
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($latestMessages->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="h-24 w-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No conversations</h3>
                        <p class="text-gray-500">You haven't started any conversations yet.</p>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($latestMessages as $message)
                                @php
                                    $otherUser = $message->sender_id === auth()->id() ? $message->recipient : $message->sender;
                                    $hasUnread = \App\Models\Message::where('conversation_id', $message->conversation_id)
                                                                   ->where('recipient_id', auth()->id())
                                                                   ->where('is_read', false)
                                                                   ->exists();
                                @endphp
                                <div class="border rounded-lg p-4 {{ $hasUnread ? 'bg-blue-50 border-blue-200' : 'bg-white' }} hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-600">
                                                        {{ substr($otherUser->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">
                                                        {{ $otherUser->name }}
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $message->property->title }}
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ Str::limit($message->body, 100) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $message->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            @if($hasUnread)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    New
                                                </span>
                                            @endif
                                            <div class="flex space-x-2">
                                                <a href="{{ route('messages.show', $message) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    View Conversation
                                                </a>
                                                @if($message->sender_id !== auth()->id())
                                                    <a href="{{ route('message-reports.create', $message) }}" 
                                                       class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                       title="Report this message">
                                                        Report
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
