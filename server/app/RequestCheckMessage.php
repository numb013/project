<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageRequestMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_list_id',
        'admin_id',
        'cast_id',
        'confirmed',
        'message',
    ];
}
