<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestMovie extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'hash_id',
        'status',
        'check_status',
        'check_memo',
    ];
}
