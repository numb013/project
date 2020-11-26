<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Viewer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'barthbay',
        'sex',
        'coin',
    ];
}
