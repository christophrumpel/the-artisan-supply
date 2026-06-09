<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('demo:reset', function () {
    $this->warn('Resetting the demo database.');

    DB::prohibitDestructiveCommands(false);

    try {
        $this->call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
    } finally {
        DB::prohibitDestructiveCommands(app()->isProduction());
    }

    $this->info('Demo reset complete.');
})->purpose('Reset and seed the demo app');
