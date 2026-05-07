<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_email',
        'subject',
        'message',
        'audio_path',
        'audio_mime_type',
        'audio_size',
        'draft_reply',
    ];
}
