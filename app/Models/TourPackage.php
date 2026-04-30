<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    protected $fillable = [
        'agency_id', 'destination_id', 'name', 'description',
        'price', 'duration', 'inclusions', 'image_path', 'status',
    ];

    public function agency() { return $this->belongsTo(User::class, 'agency_id'); }
    public function destination() { return $this->belongsTo(Destination::class); }
    public function inquiries() { return $this->hasMany(Inquiry::class, 'package_id'); }
    public function feedback() { return $this->hasMany(Feedback::class, 'package_id'); }

    public function scopeActive($query) { return $query->where('status', 'active'); }

    protected $appends = ['display_image'];

    public function getDisplayImageAttribute(): ?string
    {
        return $this->image_url ?: $this->destination?->image_url;
    }
}
