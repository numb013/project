<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinHistory extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_bank_id',
        'coin',
        'type',
        'used_point',
        'withdraw_year_month',
        'withdraw_state',
        'drawn',
        'comment',
    ];
}
