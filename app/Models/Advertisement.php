<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AdType;

class Advertisement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rl_ads_tbl';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_title',
        'ad_type',
        'start_date',
        'end_date',
        'external_url',
        'ad_text',
        'ad_image',
        'ad_video',
        'status',
        'trash',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'integer',
        'trash' => 'integer',
        'created_by' => 'integer',
    ];

    // App/Models/Advertisement.php

public function adType()
{
    return $this->belongsTo(AdType::class, 'ad_type', 'id');
}
}