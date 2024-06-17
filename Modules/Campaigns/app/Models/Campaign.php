<?php

namespace Modules\Campaigns\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Campaign\Database\Factories\CampaignFactory;

class Campaign extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'name',
        'title',
        'start_datetime',
        'end_datetime',
    ];

    /**
     * set datetime casts
     *
     * @var array
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime'
    ];


    /**
     * get all categories for a selected campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'campaigns_categories')->withPivot('sort')->orderBy('sort', 'asc');
    }

}
