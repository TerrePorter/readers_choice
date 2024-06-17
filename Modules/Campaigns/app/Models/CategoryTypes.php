<?php

namespace Modules\Campaigns\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Campaigns\Database\Factories\CategoryTypesFactory;

class CategoryTypes extends Model
{
    use HasFactory;

    protected $table = 'categories_types';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'title'
    ];

    /**
     * Get categories for a selected category type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories() {
       return $this->hasMany(Categories::class, 'type');
    }

}
