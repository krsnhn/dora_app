<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryAlbum extends Model
{
    protected $fillable = ['user_id', 'name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function memories()
    {
        return $this->hasMany(Memory::class, 'album_id');
    }

    public function coverMemory()
    {
        return $this->hasOne(Memory::class, 'album_id')->latestOfMany();
    }
}
