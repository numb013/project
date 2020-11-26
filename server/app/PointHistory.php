<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'category',
        'can_type',
        'price',
        'period',
        'descript',
        'total_post',
        'get_coin',
        'get_coin',
        'score',
    ];
}
