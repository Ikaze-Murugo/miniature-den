<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Reports & Tickets
            </h2>
            <a href="{{ route('reports.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                Submit New Report
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Reports</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['pending'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Investigating</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['investigating'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Resolved</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['resolved'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Dismissed</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['dismissed'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Your Reports</h3>
                    
                    @if($reports->count() > 0)
                        <div class="space-y-4">
                            @foreach($reports as $report)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-medium text-gray-900">
                                                <a href="{{ route('reports.show', $report) }}" class="hover:text-blue-600">
                                                    {{ $report->title }}
                                                </a>
                                            </h4>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($report->status === 'investigating') bg-blue-100 text-blue-800
                                                @elseif($report->status === 'resolved') bg-green-100 text-green-800
                                                @elseif($report->status === 'dismissed') bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($report->priority === 'urgent') bg-red-100 text-red-800
                                                @elseif($report->priority === 'high') bg-orange-100 text-orange-800
                                                @elseif($report->priority === 'medium') bg-yellow-100 text-yellow-800
                                                @elseif($report->priority === 'low') bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($report->priority) }} Priority
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($report->description, 150) }}</p>
                                        
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span>Report #{{ $report->id }}</span>
                                            <span>•</span>
                                            <span>{{ $report->created_at->format('M d, Y \a\t g:i A') }}</span>
                                            <span>•</span>
                                            <span>{{ ucfirst($report->report_type) }}</span>
                                            <span>•</span>
                                            <span>{{ ucfirst($report->category) }}</span>
                                        </div>

                                        <!-- Show latest comment if available -->
                                        @if($report->comments && $report->comments->count() > 0)
                                        <div class="mt-3 p-3 bg-blue-50 rounded-md">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-blue-900">Latest Response</span>
                                                <span class="text-xs text-blue-600">{{ $report->comments->first()->created_at->format('M d, g:i A') }}</span>
                                            </div>
                                            <p class="text-sm text-blue-800">{{ Str::limit($report->comments->first()->comment, 100) }}</p>
                                        </div>
                                        @endif

                                        <!-- Show unread notifications -->
                                        @if($report->notifications && $report->notifications->where('is_read', false)->count() > 0)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                                </svg>
                                                {{ $report->notifications->where('is_read', false)->count() }} new update{{ $report->notifications->where('is_read', false)->count() > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-col items-end space-y-2">
                                        <a href="{{ route('reports.show', $report) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Details
                                        </a>
                                        @if($report->status === 'pending' || $report->status === 'investigating')
                                        <form action="{{ route('reports.follow-up', $report) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                                Request Follow-up
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by submitting your first report.</p>
                            <div class="mt-6">
                                <a href="{{ route('reports.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Submit Report
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
