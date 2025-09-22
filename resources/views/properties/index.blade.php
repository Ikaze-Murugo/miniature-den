@extends('layouts.app')

@section('title', 'Browse Properties')
@section('description', 'Discover amazing rental properties across Rwanda. Find your perfect home today.')

@section('content')
<div id="main-content" class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-slate-900 text-white mobile-hero">
        <div class="container">
            <div class="text-center">
                <h1 class="text-heading-1 mb-4 mobile-hero">Discover Your Perfect Home</h1>
                <p class="text-body-lg max-w-2xl mx-auto mb-8">
                    Browse through thousands of rental properties across Rwanda. 
                    Find your dream home with our modern property search.
                </p>
                
                <!-- Quick Search -->
                <div class="max-w-2xl mx-auto">
                    <div class="card mobile-search-form bg-white/95 backdrop-blur-sm">
                        <form action="{{ route('properties.search') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="form-label" for="location-search">Location</label>
                                    <input type="text" 
                                           id="location-search"
                                           name="location" 
                                           placeholder="Enter city or district"
                                           class="form-input"
                                           value="{{ request('location') }}"
                                           aria-label="Search by location">
                                </div>
                                
                                <div>
                                    <label class="form-label" for="property-type-search">Property Type</label>
                                    <select id="property-type-search" name="property_type" class="form-input" aria-label="Filter by property type">
                                        <option value="">Any Type</option>
                                        <option value="house" {{ request('property_type') == 'house' ? 'selected' : '' }}>House</option>
                                        <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                        <option value="studio" {{ request('property_type') == 'studio' ? 'selected' : '' }}>Studio</option>
                                        <option value="condo" {{ request('property_type') == 'condo' ? 'selected' : '' }}>Condo</option>
                                        <option value="villa" {{ request('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="form-label" for="max-price-search">Max Price</label>
                                    <select id="max-price-search" name="max_price" class="form-input" aria-label="Filter by maximum price">
                                        <option value="">Any Price</option>
                                        <option value="50000" {{ request('max_price') == '50000' ? 'selected' : '' }}>RWF 50,000</option>
                                        <option value="100000" {{ request('max_price') == '100000' ? 'selected' : '' }}>RWF 100,000</option>
                                        <option value="200000" {{ request('max_price') == '200000' ? 'selected' : '' }}>RWF 200,000</option>
                                        <option value="500000" {{ request('max_price') == '500000' ? 'selected' : '' }}>RWF 500,000</option>
                                        <option value="1000000" {{ request('max_price') == '1000000' ? 'selected' : '' }}>RWF 1,000,000+</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-full" aria-label="Search for properties">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search Properties
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Section -->
    <div class="py-12">
        <div class="container">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h2 class="text-heading-2 mb-2">
                        @if(auth()->check() && auth()->user()->isAdmin())
                            All Properties ({{ $properties->total() }})
                        @elseif(auth()->check() && auth()->user()->isLandlord())
                            My Properties ({{ $properties->total() }})
                        @else
                            Available Properties ({{ $properties->total() }})
                        @endif
                    </h2>
                    <p class="text-body text-gray-600">
                        @if(auth()->check() && auth()->user()->isLandlord())
                            Manage your property listings
                        @else
                            Find your perfect rental home
                        @endif
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
                    @if(auth()->check() && auth()->user()->isRenter())
                        <a href="{{ route('properties.search') }}" class="btn btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Advanced Search
                        </a>
                        <a href="{{ route('properties.search-map') }}" class="btn btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Map View
                        </a>
                    @endif
                    
                    @if(auth()->check() && auth()->user()->isLandlord())
                        <a href="{{ route('properties.create.enhanced') }}" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Property
                        </a>
                    @endif
                </div>
            </div>

            @if($properties->isEmpty())
                <!-- Empty State -->
                <div class="card text-center py-16">
                    <div class="max-w-md mx-auto">
                        <svg class="h-24 w-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-heading-3 mb-4">No Properties Found</h3>
                        <p class="text-body text-gray-600 mb-8">
                            @if(auth()->check() && auth()->user()->isLandlord())
                                Start by adding your first property to get started.
                            @else
                                Check back later for new listings or try adjusting your search criteria.
                            @endif
                        </p>
                        
                        @if(auth()->check() && auth()->user()->isLandlord())
                            <a href="{{ route('properties.create.enhanced') }}" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Your First Property
                            </a>
                        @else
                            <a href="{{ route('properties.search') }}" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Try Advanced Search
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Modern Property Grid - Mobile Optimized -->
                <div class="grid mobile-property-grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($properties as $property)
                        <article class="property-card group cursor-pointer" 
                                 onclick="window.location.href='{{ route('properties.show', $property) }}'"
                                 role="button"
                                 tabindex="0"
                                 aria-label="View {{ $property->title }} property details"
                                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.location.href='{{ route('properties.show', $property) }}'}">
                            <!-- Property Image -->
                            <div class="relative overflow-hidden rounded-t-xl">
                                @if($property->images->count() > 0)
                                    <img src="{{ Storage::url($property->images->first()->path) }}" 
                                         alt="{{ $property->title }} property image"
                                         class="property-card-image"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center" role="img" aria-label="No property image available">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Price Badge -->
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold text-primary-600 shadow-sm" 
                                     aria-label="Price: RWF {{ number_format($property->price) }}">
                                    RWF {{ number_format($property->price) }}
                                </div>
                                
                                <!-- Status Badge -->
                                @if($property->status === 'active')
                                    <div class="absolute top-4 left-4 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium" 
                                         aria-label="Property is available">
                                        Available
                                    </div>
                                @elseif($property->status === 'pending')
                                    <div class="absolute top-4 left-4 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium" 
                                         aria-label="Property is pending approval">
                                        Pending
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Property Details -->
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 group-hover:text-primary-600 transition-colors">
                                    {{ $property->title }}
                                </h3>
                                
                                <p class="text-gray-600 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $property->location }}
                                </p>
                                
                                <!-- Property Features -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center space-x-4">
                                        <span class="flex items-center" aria-label="{{ $property->bedrooms }} bedrooms">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            </svg>
                                            {{ $property->bedrooms }} bed
                                        </span>
                                        <span class="flex items-center" aria-label="{{ $property->bathrooms }} bathrooms">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7"></path>
                                            </svg>
                                            {{ $property->bathrooms }} bath
                                        </span>
                                    </div>
                                    @if($property->area)
                                        <span class="flex items-center" aria-label="{{ $property->area }} square meters">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                            {{ $property->area }} m²
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Property Type -->
                                <div class="mb-4">
                                    <span class="badge badge-primary" aria-label="Property type: {{ ucfirst($property->type) }}">
                                        {{ ucfirst($property->type) }}
                                    </span>
                                </div>
                                
                                <!-- Contact Button - Mobile Optimized -->
                                <div class="pt-4 border-t border-gray-100">
                                    @if(auth()->check() && auth()->user()->isRenter())
                                        <a href="{{ route('messages.create', $property) }}" 
                                           class="btn btn-primary mobile-contact-btn text-center"
                                           onclick="event.stopPropagation()"
                                           aria-label="Contact landlord about {{ $property->title }}">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Contact Landlord
                                        </a>
                                    @elseif(!auth()->check())
                                        <a href="{{ route('login') }}" 
                                           class="btn btn-primary mobile-contact-btn text-center"
                                           onclick="event.stopPropagation()"
                                           aria-label="Login to contact landlord about {{ $property->title }}">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                            </svg>
                                            Login to Contact
                                        </a>
                                    @else
                                        <a href="{{ route('properties.show', $property) }}" 
                                           class="btn btn-outline mobile-contact-btn text-center"
                                           aria-label="View details for {{ $property->title }}">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Details
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection