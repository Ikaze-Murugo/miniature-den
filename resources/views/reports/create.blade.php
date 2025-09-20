<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Submit Report
            </h2>
            <a href="{{ route('reports.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200">
                Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Report Type (Hidden) -->
                        <input type="hidden" name="report_type" value="{{ $reportType }}">
                        
                        @if($resource)
                            <!-- Resource Information -->
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Reporting:</h3>
                                @if($reportType === 'property')
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($resource->images->count() > 0)
                                                <img src="{{ Storage::url($resource->images->first()->path) }}" 
                                                     alt="{{ $resource->title }}" 
                                                     class="h-12 w-12 rounded-lg object-cover">
                                            @else
                                                <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $resource->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $resource->location }}</p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="reported_property_id" value="{{ $resource->id }}">
                                @elseif($reportType === 'user')
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($resource->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $resource->name }}</p>
                                            <p class="text-sm text-gray-500">{{ ucfirst($resource->role) }}</p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="reported_user_id" value="{{ $resource->id }}">
                                @elseif($reportType === 'message')
                                    <div class="p-3 bg-white rounded border">
                                        <p class="text-sm text-gray-900">{{ Str::limit($resource->content, 100) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">From: {{ $resource->sender->name }}</p>
                                    </div>
                                    <input type="hidden" name="reported_message_id" value="{{ $resource->id }}">
                                @endif
                            </div>
                        @endif

                        <!-- Category Selection -->
                        <div class="mb-6">
                            <x-input-label for="category" :value="__('Report Category')" />
                            <select id="category" name="category" 
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                        {{ $category->description }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Title -->
                        <div class="mb-6">
                            <x-input-label for="title" :value="__('Report Title')" />
                            <x-text-input id="title" name="title" type="text" 
                                         class="mt-1 block w-full" 
                                         :value="old('title')" 
                                         placeholder="Brief description of the issue"
                                         required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Detailed Description')" />
                            <textarea id="description" name="description" rows="6" 
                                     class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                     placeholder="Please provide as much detail as possible about the issue..."
                                     required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Evidence Files -->
                        <div class="mb-6">
                            <x-input-label for="evidence_files" :value="__('Evidence (Optional)')" />
                            <input id="evidence_files" name="evidence_files[]" type="file" multiple
                                   accept="image/*,.pdf"
                                   class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <p class="mt-1 text-sm text-gray-500">
                                Upload screenshots, documents, or other evidence (max 5 files, 2MB each)
                            </p>
                            <x-input-error :messages="$errors->get('evidence_files')" class="mt-2" />
                        </div>

                        <!-- Priority (for certain categories) -->
                        <div class="mb-6" id="priority-section" style="display: none;">
                            <x-input-label for="priority" :value="__('Priority Level')" />
                            <select id="priority" name="priority" 
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="medium">Medium (Default)</option>
                                <option value="low">Low</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                Submit Report
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show priority section for certain categories
        document.getElementById('category').addEventListener('change', function() {
            const prioritySection = document.getElementById('priority-section');
            const highPriorityCategories = ['fraud', 'harassment'];
            
            if (highPriorityCategories.includes(this.value)) {
                prioritySection.style.display = 'block';
                document.getElementById('priority').value = 'high';
            } else {
                prioritySection.style.display = 'none';
                document.getElementById('priority').value = 'medium';
            }
        });
    </script>
</x-app-layout>
