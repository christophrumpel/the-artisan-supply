<?php

use App\Ai\KnowledgeBaseEmbeddings;
use App\Models\Faq;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('knowledge-base:embed {--force : Regenerate embeddings for all entries}', function () {
    $query = Faq::query()
        ->when(! $this->option('force'), fn ($query) => $query->whereNull('embedding'));

    $total = $query->count();
    $embedded = 0;

    $query->lazyById()->each(function (Faq $faq) use (&$embedded) {
        if (app(KnowledgeBaseEmbeddings::class)->embed($faq)) {
            $embedded++;
        }
    });

    $this->info("Embedded {$embedded} of {$total} knowledge base entries.");
})->purpose('Generate vector embeddings for knowledge base entries');

Artisan::command('demo:reset', function () {
    $this->warn('Resetting the demo database and regenerating knowledge base embeddings.');

    $this->call('migrate:fresh', [
        '--seed' => true,
        '--force' => true,
    ]);

    $this->call('knowledge-base:embed', [
        '--force' => true,
    ]);

    $this->info('Demo reset complete.');
})->purpose('Reset and seed the demo app, then regenerate knowledge base embeddings');
