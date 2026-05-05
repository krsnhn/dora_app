<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model {
    protected $fillable = ['user_id', 'album_id', 'destination_id', 'image_path', 'caption', 'travel_date'];
    protected function casts(): array { return ['travel_date' => 'date']; }
    public function user() { return $this->belongsTo(User::class); }
    public function album() { return $this->belongsTo(MemoryAlbum::class, 'album_id'); }
    public function destination() { return $this->belongsTo(Destination::class); }
}
