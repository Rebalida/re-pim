<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'platform',
        'store_url',
        'api_key',
        'api_secret',
        'status',
        'last_checked_at',
    ];
}
