<?php

namespace Database\Seeders;

use App\Models\Faq;
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

        Product::query()
            ->whereNotNull('image_path')
            ->get()
            ->each(function (Product $product) {
                $filename = basename($product->image_path);
                $path = public_path($product->image_path);

                ProductAsset::updateOrCreate(
                    ['filename' => $filename],
                    [
                        'product_id' => $product->id,
                        'file_path' => $product->image_path,
                        'mime_type' => 'image/png',
                        'size' => file_exists($path) ? filesize($path) : 0,
                        'title' => $product->name.' product image',
                        'description' => 'Seeded product image used on the '.$product->name.' product page.',
                        'alt_text' => $product->name.' product image for The Artisan Supply shop.',
                    ],
                );
            });

        collect([
            ['question' => 'Does the Artisan Wand run real commands?', 'answer' => 'No. It is decorative, but it pairs well with confident terminal usage.'],
            ['question' => 'When will my Queue Worker Lunchbox ship?', 'answer' => 'Most orders ship within 2-3 business days unless they enter the failed jobs table.'],
            ['question' => 'Can I return the Migration Time Machine?', 'answer' => 'Yes, but only before you bought it. Time travel policy is strict.'],
            ['question' => 'Why does the Artisan Wand not work?', 'answer' => 'The Artisan Wand is a decorative desk prop. It does not run shell commands, automate deployments, or connect to an API.'],
            ['question' => 'Is the Queue Worker Lunchbox dishwasher safe?', 'answer' => 'The Queue Worker Lunchbox should be hand washed only. The lid and dividers can be removed before cleaning.'],
            ['question' => 'Why is my Queue Worker Lunchbox order still processing?', 'answer' => 'Processing can take up to three business days during busy release weeks. If it has been longer, support should check the order manually.'],
            ['question' => 'Can the Migration Time Machine restore deleted production data?', 'answer' => 'No. The Migration Time Machine is a novelty product and cannot restore deleted production data or replace real backups.'],
            ['question' => 'Can I use the Migration Time Machine to roll back one table?', 'answer' => 'No. The product is decorative. Customers should use their application backup and migration workflow for real rollback work.'],
            ['question' => 'Does the Rubber Duck Debugger Pro listen to private code?', 'answer' => 'No. The Rubber Duck Debugger Pro has no microphone, network connection, or storage. It only listens in spirit.'],
            ['question' => 'How do I clean the Rubber Duck Debugger Pro?', 'answer' => 'Clean it with a soft damp cloth and mild soap. Do not put it in a dishwasher or expose it to high heat.'],
        ])->each(fn (array $faq) => Faq::updateOrCreate(
            ['question' => $faq['question']],
            ['answer' => $faq['answer']],
        ));

        collect([
            [
                'customer_name' => 'Nuno from Localhost',
                'customer_email' => 'nuno@example.com',
                'subject' => 'My lunchbox has been processing forever',
                'message' => 'Hi! I ordered the Queue Worker Lunchbox and the status says processing for three days. Is this expected or did it get stuck in a queue?',
                'draft_reply' => null,
            ],
            [
                'customer_name' => 'Jess from the Deployment Guild',
                'customer_email' => 'jess@example.com',
                'subject' => 'Can I return the Migration Time Machine?',
                'message' => 'Hello! I bought the Migration Time Machine yesterday, but I now realize I only needed to roll back one table. Can I still return it?',
                'draft_reply' => null,
            ],
        ])->each(fn (array $message) => SupportMessage::create($message));
    }
}
