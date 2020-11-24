<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category',
        'address',
        'tel',
        'email',
        'hp_url',
        'contact_name',
        'contact_tel',
        'contact_mail',
        'company_address',
        'company_tel',
        'accouont_type',
        'transfer_name',
    ];
}
