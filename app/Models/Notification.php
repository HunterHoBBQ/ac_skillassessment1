<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Add 'user_id' to the $fillable array
        'latest_bid_price',
        'user_last_bid_price',
    ];
}
