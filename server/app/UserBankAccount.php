<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBankAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cast_id',
        'company_id',
        'bank_code',
        'bank_branch_code',
        'account_no',
        'account_name',
        'accouont_type',
        'transfer_name',
    ];
}
