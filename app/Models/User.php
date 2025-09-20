<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'verification_token',
        'verification_expires_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'verification_expires_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    // Role check methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLandlord()
    {
        return $this->role === 'landlord';
    }

    public function isRenter()
    {
        return $this->role === 'renter';
    }

    // Relationships
    public function properties()
    {
        return $this->hasMany(Property::class, 'landlord_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProperties()
    {
        return $this->belongsToMany(Property::class, 'favorites')
                    ->withPivot(['list_name', 'notes', 'created_at'])
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function landlordReviews()
    {
        return $this->hasMany(Review::class, 'landlord_id');
    }

    public function emailPreferences()
    {
        return $this->hasOne(UserEmailPreference::class);
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class);
    }

    public function savedSearches()
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function propertyComparisons()
    {
        return $this->hasMany(PropertyComparison::class);
    }

    // Report relationships
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function reportedReports()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    public function reportComments()
    {
        return $this->hasMany(ReportComment::class);
    }

    public function reportNotifications()
    {
        return $this->hasMany(ReportNotification::class);
    }

    public function unreadReportNotifications()
    {
        return $this->hasMany(ReportNotification::class)->unread();
    }

    public function messageReportNotifications()
    {
        return $this->hasMany(MessageReportNotification::class);
    }

    public function unreadMessageReportNotifications()
    {
        return $this->hasMany(MessageReportNotification::class)->unread();
    }

    public function approvedLandlordReviews()
    {
        return $this->hasMany(Review::class, 'landlord_id')->where('is_approved', true);
    }

    /**
     * Get average rating as a landlord
     */
    public function getAverageLandlordRating()
    {
        return $this->approvedLandlordReviews()->avg('landlord_rating');
    }

    /**
     * Get review count as a landlord
     */
    public function getLandlordReviewCount()
    {
        return $this->approvedLandlordReviews()->count();
    }

    // Email verification methods
    /**
     * Generate a verification token for the user
     */
    public function generateVerificationToken()
    {
        $this->verification_token = bin2hex(random_bytes(32));
        $this->verification_expires_at = now()->addHours(24);
        $this->save();
        
        return $this->verification_token;
    }

    /**
     * Verify the user's email
     */
    public function verifyEmail()
    {
        $this->is_verified = true;
        $this->email_verified_at = now();
        $this->verification_token = null;
        $this->verification_expires_at = null;
        $this->save();
    }

    /**
     * Check if the user's email is verified
     */
    public function isEmailVerified()
    {
        return $this->is_verified && $this->email_verified_at !== null;
    }

    /**
     * Check if the verification token is valid
     */
    public function isVerificationTokenValid($token)
    {
        return $this->verification_token === $token && 
               $this->verification_expires_at && 
               $this->verification_expires_at->isFuture();
    }

    /**
     * Clear the verification token
     */
    public function clearVerificationToken()
    {
        $this->verification_token = null;
        $this->verification_expires_at = null;
        $this->save();
    }

    // Message Report relationships
    public function messageReports()
    {
        return $this->hasMany(MessageReport::class, 'sender_id');
    }

    public function reportedMessageReports()
    {
        return $this->hasMany(MessageReport::class, 'recipient_id');
    }

    public function messageReportComments()
    {
        return $this->hasMany(MessageReportComment::class);
    }


    // Admin role relationships
    public function adminRoles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_user_roles', 'user_id', 'role_id')
                    ->withPivot(['assigned_by', 'assigned_at', 'expires_at', 'is_active'])
                    ->wherePivot('is_active', true)
                    ->withTimestamps();
    }

    public function allAdminRoles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_user_roles', 'user_id', 'role_id')
                    ->withPivot(['assigned_by', 'assigned_at', 'expires_at', 'is_active'])
                    ->withTimestamps();
    }

    public function assignedTickets()
    {
        return $this->hasMany(TicketAssignment::class, 'assigned_to');
    }

    public function activeTickets()
    {
        return $this->assignedTickets()->active();
    }

    public function completedTickets()
    {
        return $this->assignedTickets()->byStatus('completed');
    }

    // Admin permission methods
    public function hasAdminRole(string $roleName): bool
    {
        return $this->adminRoles()->where('name', $roleName)->exists();
    }

    public function hasAdminPermission(string $permission): bool
    {
        return $this->adminRoles()
                    ->whereHas('permissions', function($query) use ($permission) {
                        $query->where('name', $permission);
                    })
                    ->exists();
    }

    public function hasAdminLevel(int $minLevel): bool
    {
        return $this->adminRoles()->where('level', '>=', $minLevel)->exists();
    }

    public function getHighestAdminLevel(): int
    {
        return $this->adminRoles()->max('level') ?? 0;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasAdminLevel(4);
    }

    public function isAdminManager(): bool
    {
        return $this->hasAdminLevel(3);
    }

    public function isSeniorAdmin(): bool
    {
        return $this->hasAdminLevel(2);
    }

    public function isJuniorAdmin(): bool
    {
        return $this->hasAdminLevel(1);
    }

    public function getAdminPermissions(): array
    {
        return $this->adminRoles()
                    ->with('permissions')
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->pluck('name')
                    ->unique()
                    ->toArray();
    }
}