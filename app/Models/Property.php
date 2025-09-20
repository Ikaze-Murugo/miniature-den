<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'amenities',
        'features',
        'area',
        'furnishing_status',
        'parking_spaces',
        'has_balcony',
        'has_garden',
        'has_pool',
        'has_gym',
        'has_security',
        'has_elevator',
        'has_air_conditioning',
        'has_heating',
        'has_internet',
        'has_cable_tv',
        'pets_allowed',
        'smoking_allowed',
        'price',
        'location',
        'type',
        'bedrooms',
        'bathrooms',
        'rejection_reason',
        'rejected_at',
        'is_available',
        'status',
        'priority',
        'is_featured',
        'featured_until',
        'view_count',
        'latitude',
        'longitude',
        'address',
        'neighborhood',
    ];

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'features' => 'array',
            'area' => 'decimal:2',
            'parking_spaces' => 'integer',
            'has_balcony' => 'boolean',
            'has_garden' => 'boolean',
            'has_pool' => 'boolean',
            'has_gym' => 'boolean',
            'has_security' => 'boolean',
            'has_elevator' => 'boolean',
            'has_air_conditioning' => 'boolean',
            'has_heating' => 'boolean',
            'has_internet' => 'boolean',
            'has_cable_tv' => 'boolean',
            'pets_allowed' => 'boolean',
            'smoking_allowed' => 'boolean',
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'featured_until' => 'datetime',
            'view_count' => 'integer',
            'rejected_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(Image::class)->where('is_primary', true);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')
                    ->withPivot(['list_name', 'notes', 'created_at'])
                    ->withTimestamps();
    }

    /**
     * Check if property is favorited by a specific user
     */
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Get the favorite record for a specific user
     */
    public function getFavoriteForUser($userId)
    {
        return $this->favorites()->where('user_id', $userId)->first();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get average rating for this property
     */
    public function getAverageRating()
    {
        return $this->approvedReviews()->avg('property_rating');
    }

    /**
     * Scope for featured properties
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where(function($q) {
                        $q->whereNull('featured_until')
                          ->orWhere('featured_until', '>', now());
                    });
    }

    /**
     * Scope for high priority properties
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * Scope for properties ordered by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END")
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Check if property is currently featured
     */
    public function isCurrentlyFeatured()
    {
        return $this->is_featured && 
               (is_null($this->featured_until) || $this->featured_until > now());
    }

    /**
     * Get review count for this property
     */
    public function getReviewCount()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get the property amenities (cached proximity data)
     */
    public function propertyAmenities()
    {
        return $this->hasMany(PropertyAmenity::class);
    }

    /**
     * Get nearby amenities for this property
     */
    public function nearbyAmenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities')
            ->withPivot('distance_km', 'walking_time_minutes', 'driving_time_minutes')
            ->withTimestamps()
            ->orderBy('distance_km');
    }

    /**
     * Get blueprints for this property
     */
    public function blueprints()
    {
        return $this->hasMany(Image::class)->where('image_type', 'blueprint');
    }

    /**
     * Get images by type
     */
    public function imagesByType($type)
    {
        return $this->hasMany(Image::class)->where('image_type', $type);
    }

    /**
     * Check if property has coordinates
     */
    public function hasCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        if ($this->address) {
            return $this->address;
        }
        
        return $this->location;
    }

    /**
     * Scope for properties with coordinates
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Scope for properties in a specific neighborhood
     */
    public function scopeInNeighborhood($query, $neighborhood)
    {
        return $query->where('neighborhood', 'like', "%{$neighborhood}%");
    }
}