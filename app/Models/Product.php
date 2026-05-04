<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'price_cents',
        'badge',
        'emoji',
        'image_path',
        'color',
        'inventory',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'price_cents' => 'integer',
            'inventory' => 'integer',
        ];
    }

    public function price(): string
    {
        return '€'.number_format($this->price_cents / 100, 2);
    }
}
