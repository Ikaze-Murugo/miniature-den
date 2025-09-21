@extends('layouts.app')

@section('title', 'Browse Properties - Find Your Perfect Home in Rwanda')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Find Your Perfect Home
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Discover thousands of rental properties across Rwanda. From cozy apartments to spacious houses, find your ideal home today.
                </p>
                
                <!-- Quick Search Bar -->
                <div class="max-w-4xl mx-auto">
                    <form action="{{ route('properties.public.search') }}" method="GET" class="bg-white rounded-lg shadow-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <input type="text" 
                                       name="search" 
                                       placeholder="Enter city, district, or neighborhood"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ request('search') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                                <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Any Type</option>
                                    @foreach($filterOptions['types'] as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Search Properties
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Filter Toggle -->
                <button id="filterToggle" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    <span>Filters</span>
                </button>

                <!-- View Toggle -->
                <div class="flex items-center space-x-2">
                    <button id="gridView" class="p-2 text-gray-400 hover:text-gray-600 active">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button id="listView" class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select id="sortSelect" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="newest">Newest First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="size_large">Size: Largest First</option>
                        <option value="size_small">Size: Smallest First</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>
            </div>

            <!-- Advanced Filters Panel -->
            <div id="filtersPanel" class="hidden mt-6 pt-6 border-t border-gray-200">
                <form action="{{ route('properties.public.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="space-y-2">
                            <input type="number" name="min_price" placeholder="Min Price" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ request('min_price') }}">
                            <input type="number" name="max_price" placeholder="Max Price" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <!-- Bedrooms -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                        <select name="bedrooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Any</option>
                            <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+</option>
                            <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+</option>
                            <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+</option>
                            <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+</option>
                            <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5+</option>
                        </select>
                    </div>

                    <!-- Bathrooms -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                        <select name="bathrooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Any</option>
                            <option value="1" {{ request('bathrooms') == '1' ? 'selected' : '' }}>1+</option>
                            <option value="2" {{ request('bathrooms') == '2' ? 'selected' : '' }}>2+</option>
                            <option value="3" {{ request('bathrooms') == '3' ? 'selected' : '' }}>3+</option>
                            <option value="4" {{ request('bathrooms') == '4' ? 'selected' : '' }}>4+</option>
                        </select>
                    </div>

                    <!-- Furnishing Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Furnishing</label>
                        <select name="furnishing_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Any</option>
                            @foreach($filterOptions['furnishing_statuses'] as $status)
                                <option value="{{ $status }}" {{ request('furnishing_status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Amenities -->
                    <div class="md:col-span-2 lg:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach($filterOptions['amenities'] as $amenity)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           {{ in_array($amenity->id, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $amenity->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-4">
                        <a href="{{ route('properties.public.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Clear Filters
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Properties Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Results Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $properties->total() }} Properties Found
                </h2>
                @if(request()->hasAny(['search', 'type', 'min_price', 'max_price', 'bedrooms', 'bathrooms']))
                    <p class="text-gray-600 mt-1">
                        Showing results for your search criteria
                    </p>
                @endif
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('properties.public.map') }}" class="flex items-center space-x-2 text-blue-600 hover:text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Map View</span>
                </a>
            </div>
        </div>

        @if($properties->count() > 0)
            <!-- Properties Grid -->
            <div id="propertiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Property Image -->
                        <div class="relative h-48 bg-gray-200">
                            @if($property->images->count() > 0)
                                        <img src="{{ asset('storage/' . $property->images->first()->path) }}" 
                                             alt="{{ $property->title }}"
                                             class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Available
                                </span>
                            </div>
                            
                            <!-- Favorite Button -->
                            <button class="absolute top-3 right-3 p-2 bg-white rounded-full shadow-md hover:bg-gray-50 transition-colors">
                                <svg class="h-5 w-5 text-gray-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Property Details -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">
                                    {{ $property->title }}
                                </h3>
                                <span class="text-lg font-bold text-blue-600">
                                    RWF {{ number_format($property->price) }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $property->description }}
                            </p>
                            
                            <div class="flex items-center text-gray-500 text-sm mb-3">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $property->neighborhood ?? $property->address }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center space-x-4">
                                    <span>{{ $property->bedrooms }} bed</span>
                                    <span>{{ $property->bathrooms }} bath</span>
                                    @if($property->area)
                                        <span>{{ $property->area }}m²</span>
                                    @endif
                                </div>
                                <span class="capitalize">{{ $property->type }}</span>
                            </div>
                            
                            <a href="{{ route('properties.public.show', $property->id) }}" 
                               class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center block">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $properties->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Try adjusting your search criteria or browse all properties.
                </p>
                <div class="mt-6">
                    <a href="{{ route('properties.public.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Browse All Properties
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- JavaScript for interactivity -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter toggle
    const filterToggle = document.getElementById('filterToggle');
    const filtersPanel = document.getElementById('filtersPanel');
    
    filterToggle.addEventListener('click', function() {
        filtersPanel.classList.toggle('hidden');
    });
    
    // View toggle
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const propertiesGrid = document.getElementById('propertiesGrid');
    
    gridView.addEventListener('click', function() {
        gridView.classList.add('active');
        listView.classList.remove('active');
        propertiesGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
    });
    
    listView.addEventListener('click', function() {
        listView.classList.add('active');
        gridView.classList.remove('active');
        propertiesGrid.className = 'grid grid-cols-1 gap-6';
    });
    
    // Sort functionality
    const sortSelect = document.getElementById('sortSelect');
    sortSelect.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('sort', this.value);
        window.location.href = url.toString();
    });
});
</script>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.active {
    color: #2563eb !important;
}
</style>
@endsection
