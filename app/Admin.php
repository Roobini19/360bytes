<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    
    protected $hidden = ['updated_at', 'created_at', 'remember_token', 'password'];
}
