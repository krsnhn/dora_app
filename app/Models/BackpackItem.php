<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackpackItem extends Model
{
    protected $table = 'backpack_items';
    
    protected $fillable = [
        'user_id',
        'name',
        'category',
        'group_name',
        'is_checked',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}