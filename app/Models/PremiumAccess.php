<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumAccess extends Model
{
    protected $table = 'premium_access';

    protected $fillable = [
        'user_id',
        'payment_id',
        'type',
        'starts_at',
        'ends_at'
    ];
}