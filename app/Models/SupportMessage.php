<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected function casts(): array
    {
        return [
            'transcribed_at' => 'datetime',
        ];
    }

    protected $fillable = [
        'customer_name',
        'customer_email',
        'subject',
        'message',
        'audio_path',
        'audio_mime_type',
        'audio_size',
        'transcription',
        'transcribed_at',
        'draft_reply',
    ];
}
