<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->string('audio_path')->nullable()->after('message');
            $table->string('audio_mime_type')->nullable()->after('audio_path');
            $table->unsignedInteger('audio_size')->nullable()->after('audio_mime_type');
        });
    }
};
