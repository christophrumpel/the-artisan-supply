<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('shop.index', [
        'featuredProducts' => Product::query()->where('featured', true)->get(),
        'products' => Product::query()->orderByDesc('featured')->orderBy('name')->get(),
    ]);
})->name('home');

Route::get('/products/{product:slug}', function (Product $product) {
    return view('shop.show', [
        'product' => $product,
        'relatedProducts' => Product::query()
            ->whereKeyNot($product->id)
            ->inRandomOrder()
            ->limit(3)
            ->get(),
    ]);
})->name('products.show');

Route::get('/support', function () {
    return view('shop.support', [
        'questions' => [
            [
                'question' => 'Does the Artisan Wand run real commands?',
                'answer' => 'Not yet. For legal and emotional reasons it is currently decorative only.',
            ],
            [
                'question' => 'Can I return the Migration Time Machine?',
                'answer' => 'Yes, but only before you purchased it. Time travel rules are strict.',
            ],
            [
                'question' => 'Do you ship failed jobs separately?',
                'answer' => 'We retry them three times, then send a very apologetic postcard.',
            ],
        ],
    ]);
})->name('support');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
