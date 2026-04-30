<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model {
    /**
     * Check if the currently authenticated user has favorited this destination.
     * @return bool
     */
    public function isFavorited(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }

    use HasFactory;

    protected $fillable = [
        'name', 'country', 'location', 'description', 'image_path',
        'tags', 'latitude', 'longitude', 'weather_location', 'is_approved', 'created_by',
    ];

    protected function casts(): array
    {
        return ['is_approved' => 'boolean', 'latitude' => 'float', 'longitude' => 'float'];
    }

    public function getTagsArrayAttribute(): array
    {
        return $this->tags ? array_filter(array_map('trim', explode(',', $this->tags))) : [];
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function tourPackages() { return $this->hasMany(TourPackage::class); }
    public function favorites() { return $this->hasMany(Favorite::class); }
    public function memories() { return $this->hasMany(Memory::class); }
    public function favoritedBy() { return $this->belongsToMany(User::class, 'favorites'); }

    public function scopeApproved($query) { return $query->where('is_approved', true); }
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('country', 'like', "%{$term}%")
              ->orWhere('tags', 'like', "%{$term}%")
              ->orWhere('location', 'like', "%{$term}%");
        });
    }
}
