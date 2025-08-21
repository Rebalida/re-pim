<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'compare_at_price',
        'cost',
        'charge_tax',
        'sku',
        'barcode',
        'quantity',
        'type',
        'vendor',
        'collections',
        'tags',
        'status',
    ];

    protected $casts = [
        'charge_tax'        => 'boolean',
        'price'             => 'decimal:2',
        'compare_at_price'  => 'decimal:2',
        'cost'              => 'decimal:2',
    ];

    public function images(): HasMany 
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class)->withPivot([
            'remote_product_id',
            'remote_payload',
            'sync_status',
            'last_error',
            'last_synced_at',
        ])->withTimestamps();
    }
}
