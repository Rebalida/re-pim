<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot([
            'remote_product_id',
            'remote_payload',
            'sync_status',
            'last_error',
            'last_synced_at'
        ])->withTimestamps();
    }
}
