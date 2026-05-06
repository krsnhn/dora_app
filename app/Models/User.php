<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status',
        'business_name', 'facebook_page', 'phone', 'address',
        'valid_id_path', 'verification_notes', 'agency_status',
        'profile_photo',
    ];

    public function profilePhotoUrl(): string
    {
        return $this->profile_photo
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6b4c35&color=fff&size=128';
    }

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isTraveler(): bool { return $this->role === 'traveler'; }
    public function isAgency(): bool { return $this->role === 'agency'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isApprovedAgency(): bool { return $this->role === 'agency' && $this->agency_status === 'approved'; }

    public function destinations() { return $this->hasMany(Destination::class, 'created_by'); }
    public function tourPackages() { return $this->hasMany(TourPackage::class, 'agency_id'); }
    public function inquiries() { return $this->hasMany(Inquiry::class); }
    public function agencyInquiries() { return $this->hasMany(Inquiry::class, 'agency_id'); }
    public function favorites() { return $this->hasMany(Favorite::class); }
    public function favoriteDestinations() { return $this->belongsToMany(Destination::class, 'favorites'); }
    public function memories() { return $this->hasMany(Memory::class); }
    public function memoryAlbums() { return $this->hasMany(MemoryAlbum::class); }
    public function feedback() { return $this->hasMany(Feedback::class); }
    public function backpackItems() { return $this->hasMany(BackpackItem::class); }
    public function backpackGroups() { return $this->hasMany(BackpackGroup::class); }
    public function destinationRequests() { return $this->hasMany(DestinationRequest::class, 'agency_id'); }
    public function agencyFeedback() { return $this->hasMany(Feedback::class, 'agency_id'); }
}
