<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Conversation with {{ $otherParticipant->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $conversationMessages->count() }} message{{ $conversationMessages->count() !== 1 ? 's' : '' }}
                </p>
            </div>
            <a href="{{ route('messages.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200">
                Back to Conversations
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Conversation Thread -->
                    <div class="space-y-4 mb-8">
                        @foreach($conversationMessages as $msg)
                            <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="flex items-center space-x-2 mb-1 {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600">
                                                {{ substr($msg->sender->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $msg->sender->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $msg->created_at->format('M j, g:i A') }}</span>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-blue-100' : 'bg-gray-100' }}">
                                        <p class="text-gray-800 whitespace-pre-wrap">{{ $msg->body }}</p>
                                        @if($msg->property)
                                            <div class="mt-2 pt-2 border-t border-gray-200">
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium">About:</span> 
                                                    <a href="{{ route('properties.show', $msg->property) }}" 
                                                       class="text-blue-600 hover:text-blue-800">
                                                        {{ $msg->property->title }}
                                                    </a>
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <!-- Report Button - Only show for messages from other users -->
                                        @if($msg->sender_id !== auth()->id())
                                            <div class="mt-2 pt-2 border-t border-gray-200 flex justify-end">
                                                <a href="{{ route('message-reports.create', $msg) }}" 
                                                   class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-red-600 hover:text-red-800 hover:bg-red-50 transition-colors duration-200"
                                                   title="Report this message">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    Report
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Reply Form - Available for both users in the conversation -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Send a message to {{ $otherParticipant->name }}</h4>
                        <form method="POST" action="{{ route('messages.reply', $conversationMessages->first()) }}">
                            @csrf
                            <div class="mb-4">
                                <textarea name="body" rows="4" 
                                         class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                         placeholder="Type your message here..."
                                         required>{{ old('body') }}</textarea>
                                @error('body')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <x-primary-button type="submit">
                                    {{ __('Send Message') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
