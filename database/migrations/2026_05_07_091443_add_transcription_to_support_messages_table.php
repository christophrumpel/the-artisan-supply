<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->text('transcription')->nullable()->after('audio_size');
            $table->timestamp('transcribed_at')->nullable()->after('transcription');
        });
    }
};
