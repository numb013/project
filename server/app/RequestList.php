<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'cast_id',
        'status',
        'category',
        'category1',
        'to_name',
        'request_detail',
        'created_at',
    ];
    protected $dates = [
        'created_at'
    ];
}
