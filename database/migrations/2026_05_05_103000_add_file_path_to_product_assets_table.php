<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('product_assets', 'file_path')) {
            Schema::table('product_assets', function (Blueprint $table) {
                $table->string('file_path')->nullable()->after('filename');
            });
        }
    }
};
