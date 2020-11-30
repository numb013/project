<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cast_id',
        'hash_id',
        'target_type',
        'state',
    ];
}
