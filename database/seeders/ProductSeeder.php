<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'name' => 'Artisan Wand',
                'slug' => 'artisan-wand',
                'tagline' => 'For commands that deserve a little drama.',
                'description' => 'A handcrafted maple wand for developers who whisper php artisan before solving production issues. Does not actually clear cache, but looks very convincing on your desk.',
                'price_cents' => 2900,
                'badge' => 'Bestseller',
                'emoji' => '🪄',
                'color' => 'from-red-500 to-orange-400',
                'inventory' => 13,
                'featured' => true,
            ],
            [
                'name' => 'Queue Worker Lunchbox',
                'slug' => 'queue-worker-lunchbox',
                'tagline' => 'Keeps snacks warm while jobs retry.',
                'description' => 'A durable lunchbox with three compartments: pending, processing, and failed. Comes with a tiny sticker that says “restart your workers”.',
                'price_cents' => 4200,
                'badge' => 'Ships async',
                'emoji' => '🍱',
                'color' => 'from-amber-400 to-pink-500',
                'inventory' => 8,
                'featured' => true,
            ],
            [
                'name' => 'Eloquent Relationship Counselor',
                'slug' => 'eloquent-relationship-counselor',
                'tagline' => 'For models that are complicated.',
                'description' => 'A pocket-sized card deck that helps you decide between hasOne, hasMany, belongsTo, and “maybe this should be a service class”.',
                'price_cents' => 1800,
                'badge' => 'New',
                'emoji' => '💞',
                'color' => 'from-fuchsia-500 to-purple-600',
                'inventory' => 21,
                'featured' => false,
            ],
            [
                'name' => 'Migration Time Machine',
                'slug' => 'migration-time-machine',
                'tagline' => 'Go back before you added that nullable column.',
                'description' => 'A tiny brass desk ornament for those rare moments when rollback is more of a lifestyle than a command.',
                'price_cents' => 9900,
                'badge' => 'No down() included',
                'emoji' => '⏳',
                'color' => 'from-cyan-400 to-blue-600',
                'inventory' => 3,
                'featured' => true,
            ],
            [
                'name' => 'Blade Component Polish',
                'slug' => 'blade-component-polish',
                'tagline' => 'For suspiciously elegant UI fragments.',
                'description' => 'A completely imaginary spray that makes anonymous components feel premium. Works best with slots, props, and unreasonable confidence.',
                'price_cents' => 1500,
                'badge' => 'Tiny bottle',
                'emoji' => '✨',
                'color' => 'from-emerald-400 to-teal-500',
                'inventory' => 34,
                'featured' => false,
            ],
            [
                'name' => 'Rubber Duck Debugger Pro',
                'slug' => 'rubber-duck-debugger-pro',
                'tagline' => 'Listens patiently. Judges silently.',
                'description' => 'A premium debugging duck wearing a tiny Laravel-red scarf. It has heard every possible explanation for why the test passes locally.',
                'price_cents' => 2400,
                'badge' => 'Quacks in PHP',
                'emoji' => '🦆',
                'color' => 'from-yellow-300 to-red-500',
                'inventory' => 16,
                'featured' => true,
            ],
        ])->each(fn (array $product) => Product::updateOrCreate(
            ['slug' => $product['slug']],
            $product,
        ));
    }
}
