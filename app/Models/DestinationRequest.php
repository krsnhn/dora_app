<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DestinationRequest extends Model {
    protected $fillable = [
        'agency_id','name','country','location','description',
        'image_path','image_url','tags','latitude','longitude','weather_location','status','admin_notes'
    ];
    public function agency() { return $this->belongsTo(User::class, 'agency_id'); }
}
