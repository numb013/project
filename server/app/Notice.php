<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'send_user_type',
        'confirmed',
        'category',
        'message',
    ];
}
