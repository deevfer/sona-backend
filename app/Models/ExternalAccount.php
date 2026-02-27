<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalAccount extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'scopes',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'scopes' => 'array', 
    ];
}