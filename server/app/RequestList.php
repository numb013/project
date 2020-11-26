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
        'viewer_id',
        'cast_id',
        'status',
        'category',
        'to_name',
        'message',
        'created_at',
    ];
    protected $dates = [
        'created_at'
    ];
}
