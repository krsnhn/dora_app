<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackpackItem extends Model
{
    protected $table = 'backpack_items';
    
    protected $fillable = [
        'user_id',
        'group_id',
        'item_name',
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

    public function group()
    {
        return $this->belongsTo(BackpackGroup::class, 'group_id');
    }

    public function getNameAttribute(): string
    {
        return $this->item_name;
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['item_name'] = $value;
    }
}
