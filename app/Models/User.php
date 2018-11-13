<?php

namespace Plugins\Foundry\User\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class user
 *
 * @property $first_name
 * @property $last_name
 * @property $email
 * @property $password
 * @property $deleted_at
 * @property $updated_at
 * @property $created_at
 *
 * @package Plugins\Foundry\User\Models
 *
 */
class user extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
