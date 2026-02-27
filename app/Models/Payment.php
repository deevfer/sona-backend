<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_transaction_id',
        'amount',
        'currency',
        'status',
        'raw_response'
    ];
}