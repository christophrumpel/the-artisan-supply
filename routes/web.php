<?php

use App\Models\Faq;
use App\Models\ImageRequest;
use App\Models\Product;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('shop.index', [
        'featuredProducts' => Product::query()->where('featured', true)->get(),
        'products' => Product::query()->orderByDesc('featured')->orderBy('name')->limit(4)->get(),
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

Route::get('/studio', function () {
    return view('shop.studio', [
        'assets' => ProductAsset::with('product')->latest()->get(),
        'imageRequests' => ImageRequest::with('product')->latest()->get(),
        'supportMessages' => SupportMessage::latest()->get(),
        'faqs' => Faq::latest()->get(),
    ]);
})->name('studio');

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
