<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAsset extends Model
{
    protected $fillable = [
        'product_id',
        'filename',
        'mime_type',
        'size',
        'title',
        'description',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
