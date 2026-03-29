<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'rl_properties_tbl';
    protected $fillable = [
        'property_type',
        'location',
        'location_highlights',
        'price',
        'price_type',
        'size',
        'facing',
        'bhk_type',
        'floors',
        'community_type',
        'road_size',
        'car_parking',
        'amenities',
        'approved_by',
        'rera_status',
        'land_type',
        'owner_type',
        'conversion_type',
        'images',
        'video',
        'status',
        'trash',
        'created_by'
    ];

    protected $casts = ['images' => 'array']; // Automatically handle JSON images

    public function bhk()
    {
        return $this->belongsTo(BhkType::class, 'bhk_type');
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floors');
    }

    public function facingDirection()
    {
        return $this->belongsTo(Facing::class, 'facing');
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class, 'car_parking');
    }
    public function roadSize()
    {
        return $this->belongsTo(RoadSize::class, 'road_size');
    }
}
