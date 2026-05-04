<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'embedding',
        'embedding_model',
    ];

    protected function casts(): array
    {
        return [
            'embedding' => 'array',
        ];
    }
}
