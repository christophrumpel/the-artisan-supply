<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('image_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->text('prompt');
            $table->string('status')->default('draft');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->timestamps();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('subject');
            $table->text('message');
            $table->text('draft_reply')->nullable();
            $table->timestamps();
        });
    }
};
