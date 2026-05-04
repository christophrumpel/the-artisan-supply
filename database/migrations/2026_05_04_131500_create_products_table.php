<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline');
            $table->text('description');
            $table->unsignedInteger('price_cents');
            $table->string('badge')->nullable();
            $table->string('emoji', 16);
            $table->string('color');
            $table->unsignedInteger('inventory')->default(0);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }
};
