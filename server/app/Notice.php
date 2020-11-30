<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'cast_id',
        'type',
        'confirmed',
        'category',
        'message',
    ];
}
