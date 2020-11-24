<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteUser extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'viewer_id',
        'cast_id',
    ];
}
