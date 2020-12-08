<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryMaster extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'is_offical',
        'is_ng',
        'cast_count',
        'created_at',
    ];
}
