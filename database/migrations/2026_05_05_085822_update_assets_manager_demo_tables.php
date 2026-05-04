<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('product_assets', 'alt_text')) {
            Schema::table('product_assets', function (Blueprint $table) {
                $table->text('alt_text')->nullable()->after('description');
            });
        }

        Schema::dropIfExists('image_requests');
    }
};
