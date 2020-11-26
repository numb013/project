<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cast_id',
        'hash_id',
        'category',
        'status',
        'check_status',
        'check_memo',
    ];
}
