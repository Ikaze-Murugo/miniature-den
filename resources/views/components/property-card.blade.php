@props(['property'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <!-- Property Image -->
    <div class="h-48 bg-gray-300 flex items-center justify-center relative overflow-hidden">
        @if($property->primaryImage)
            <img src="{{ Storage::url($property->primaryImage->path) }}" 
                 alt="{{ $property->primaryImage->alt_text ?? $property->title }}"
                 class="w-full h-full object-cover">
        @else
            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
        @endif
        
        <!-- Image count badge -->
        @if($property->images && $property->images->count() > 1)
            <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                {{ $property->images->count() }} photos
            </div>
        @endif
        
        <!-- Favorite Button (for renters) -->
        @if(auth()->user()->isRenter())
            <div class="absolute top-2 left-2">
                <button onclick="toggleFavorite({{ $property->id }})" 
                        class="bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full transition-all duration-200"
                        id="favorite-btn-{{ $property->id }}">
                    @if($property->isFavoritedBy(auth()->id()))
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-gray-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    @endif
                </button>
            </div>
        @endif
    </div>
    
    <div class="p-4">
        <!-- Property Title -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $property->title }}</h3>
        
        <!-- Price -->
        <p class="text-2xl font-bold text-blue-600 mb-2">
            {{ number_format($property->price) }} RWF
            <span class="text-sm font-normal text-gray-500">/month</span>
        </p>
        
        <!-- Location -->
        <p class="text-gray-600 mb-2 flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ $property->location }}
        </p>
        
        <!-- Property Details -->
        <div class="flex items-center text-sm text-gray-500 mb-3 space-x-4">
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1M21 3l-9 9-9-9"></path>
                </svg>
                {{ $property->bedrooms }} Beds
            </span>
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21l4-4 4 4m-4-4v3"></path>
                </svg>
                {{ $property->bathrooms }} Baths
            </span>
        </div>
        
        <!-- Status Badges -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex space-x-2">
                @if($property->status === 'active')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                @elseif($property->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Rejected
                    </span>
                @endif
                
                @if($property->is_available)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Available
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Occupied
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Landlord Info (for admin/renters) -->
        @if(auth()->user()->isAdmin() || auth()->user()->isRenter())
            <p class="text-sm text-gray-500 mb-3">
                By: {{ $property->landlord->name }}
            </p>
        @endif
        
        <!-- Action Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('properties.show', $property) }}" 
               class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200">
                View Details
            </a>
            
            @if(auth()->user()->isLandlord() && $property->landlord_id === auth()->id())
                <a href="{{ route('properties.edit', $property) }}" 
                   class="bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors duration-200">
                    Edit
                </a>
            @endif
            
            @if(auth()->user()->isAdmin())
                <a href="{{ route('properties.edit', $property) }}" 
                   class="bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors duration-200">
                    Manage
                </a>
            @endif
        </div>
        
        <!-- Contact Button for Renters -->
        @if(auth()->user()->isRenter() && $property->status === 'active' && $property->is_available)
            <div class="mt-3">
                <a href="{{ route('messages.create', $property) }}" 
                   class="w-full bg-green-600 text-white text-center py-2 px-4 rounded-md hover:bg-green-700 transition-colors duration-200 block">
                    Contact Landlord
                </a>
            </div>
        @endif
    </div>
</div>

@if(auth()->user()->isRenter())
<script>
function toggleFavorite(propertyId) {
    const button = document.getElementById(`favorite-btn-${propertyId}`);
    const isFavorited = button.querySelector('svg').getAttribute('fill') === 'currentColor';
    
    const url = isFavorited 
        ? `/properties/${propertyId}/favorite`
        : `/properties/${propertyId}/favorite`;
    
    const method = isFavorited ? 'DELETE' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the button icon
            const svg = button.querySelector('svg');
            if (isFavorited) {
                // Remove from favorites - show outline heart
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
                svg.setAttribute('class', 'h-5 w-5 text-gray-400 hover:text-red-500');
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
            } else {
                // Add to favorites - show filled heart
                svg.setAttribute('fill', 'currentColor');
                svg.removeAttribute('stroke');
                svg.setAttribute('class', 'h-5 w-5 text-red-500');
                svg.innerHTML = '<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>';
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating favorites.');
    });
}
</script>
@endif