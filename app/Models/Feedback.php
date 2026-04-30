<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {
    protected $fillable = ['user_id', 'agency_id', 'package_id', 'rating', 'comment', 'status'];
    public function user() { return $this->belongsTo(User::class); }
    public function agency() { return $this->belongsTo(User::class, 'agency_id'); }
    public function package() { return $this->belongsTo(TourPackage::class, 'package_id'); }
    public function scopeApproved($query) { return $query->where('status', 'approved'); }
}
