<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'agency_id', 'contact_name', 'contact_email',
        'contact_phone', 'pax', 'message', 'travel_date', 'status',
    ];

    protected function casts(): array
    {
        return ['travel_date' => 'date'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function package() { return $this->belongsTo(TourPackage::class, 'package_id'); }
    public function agency() { return $this->belongsTo(User::class, 'agency_id'); }
}
