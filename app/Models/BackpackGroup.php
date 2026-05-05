<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackpackGroup extends Model
{
    protected $fillable = ['user_id', 'title', 'travel_date', 'start_date', 'end_date'];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function getTripDaysAttribute(): ?int
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(BackpackItem::class, 'group_id');
    }
}
