<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\ImageRequest;
use App\Models\Product;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Christoph',
            'email' => 'christoph@test.com',
            'password' => 'password',
        ]);

        $this->call(ProductSeeder::class);

        $wand = Product::where('slug', 'artisan-wand')->first();
        $lunchbox = Product::where('slug', 'queue-worker-lunchbox')->first();

        ProductAsset::create([
            'product_id' => $wand->id,
            'filename' => 'artisan-wand-hero.png',
            'mime_type' => 'image/png',
            'size' => 1824000,
            'title' => null,
            'description' => null,
        ]);

        ImageRequest::create([
            'product_id' => $lunchbox->id,
            'prompt' => 'A premium product photo of a Laravel-red queue worker lunchbox on a developer desk.',
            'status' => 'draft',
        ]);

        collect([
            ['question' => 'Does the Artisan Wand run real commands?', 'answer' => 'No. It is decorative, but it pairs well with confident terminal usage.'],
            ['question' => 'When will my Queue Worker Lunchbox ship?', 'answer' => 'Most orders ship within 2-3 business days unless they enter the failed jobs table.'],
            ['question' => 'Can I return the Migration Time Machine?', 'answer' => 'Yes, but only before you bought it. Time travel policy is strict.'],
        ])->each(fn (array $faq) => Faq::create($faq));

        SupportMessage::create([
            'customer_name' => 'Nuno from Localhost',
            'customer_email' => 'nuno@example.com',
            'subject' => 'My lunchbox has been processing forever',
            'message' => 'Hi! I ordered the Queue Worker Lunchbox and the status says processing for three days. Is this expected or did it get stuck in a queue?',
            'draft_reply' => null,
        ]);
    }
}
