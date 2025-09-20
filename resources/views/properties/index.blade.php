<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(auth()->user()->isAdmin())
                    {{ __('All Properties') }}
                @elseif(auth()->user()->isLandlord())
                    {{ __('My Properties') }}
                @else
                    {{ __('Available Properties') }}
                @endif
            </h2>
            
            <div class="flex space-x-2">
                @if(auth()->user()->isRenter())
                    <a href="{{ route('properties.search') }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors duration-200">
                        Search Properties
                    </a>
                    <a href="{{ route('properties.search-map') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        Map Search
                    </a>
                @endif
                
                @if(auth()->user()->isLandlord())
                    <a href="{{ route('properties.create.enhanced') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        Add New Property
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($properties->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="h-24 w-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21l4-4 4 4"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No properties found</h3>
                        <p class="text-gray-500 mb-4">
                            @if(auth()->user()->isLandlord())
                                Start by adding your first property to get started.
                            @else
                                Check back later for new listings.
                            @endif
                        </p>
                        
                        @if(auth()->user()->isLandlord())
                            <a href="{{ route('properties.create') }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                Add Your First Property
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Properties Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($properties as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>