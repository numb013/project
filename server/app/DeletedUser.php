<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeletedUser extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'hash_id',
        'name',
        'coin',
        'commpany_id',
    ];
}
