<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contact Landlord') }}
            </h2>
            <a href="{{ route('properties.show', $property) }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200">
                Back to Property
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Property Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-1">{{ $property->location }}</p>
                        <p class="text-lg font-semibold text-blue-600">{{ number_format($property->price) }} RWF/month</p>
                        <p class="text-sm text-gray-500 mt-2">Landlord: {{ $property->landlord->name }}</p>
                    </div>

                    <form method="POST" action="{{ route('messages.store', $property) }}">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-4">
                            <x-input-label for="subject" :value="__('Subject')" />
                            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" 
                                         :value="old('subject', 'Inquiry about ' . $property->title)" required autofocus />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <!-- Message Body -->
                        <div class="mb-4">
                            <x-input-label for="body" :value="__('Message')" />
                            <textarea id="body" name="body" rows="6" 
                                     class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                     placeholder="Hi, I'm interested in this property. Could you please provide more information about..."
                                     required>{{ old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <!-- Information Notice -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Message Guidelines</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Be polite and professional in your message</li>
                                            <li>Include your contact information if you want to be reached directly</li>
                                            <li>Ask specific questions about the property</li>
                                            <li>Mention your preferred move-in date if applicable</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Send Message') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
