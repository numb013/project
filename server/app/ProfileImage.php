<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cast_id',
        'user_id',
        'hash_id',
    ];
}
