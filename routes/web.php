<?php

use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::redirect('/studio', '/dashboard')->name('studio');

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
    Route::get('dashboard', function () {
        return view('dashboard', [
            'assetCount' => ProductAsset::count(),
            'supportMessageCount' => SupportMessage::count(),
            'draftedReplyCount' => SupportMessage::query()->whereNotNull('draft_reply')->count(),
            'faqCount' => Faq::count(),
        ]);
    })->name('dashboard');

    Route::get('dashboard/assets', function () {
        return view('dashboard.assets', [
            'products' => Product::query()->orderByDesc('featured')->orderBy('name')->get(),
            'assets' => ProductAsset::with('product')->latest()->get(),
        ]);
    })->name('dashboard.assets.index');

    Route::post('dashboard/assets', function (Request $request) {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'asset' => ['required', 'file', 'max:10240'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'alt_text' => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $request->file('asset');
        $fallbackTitle = Str::of($file->getClientOriginalName())
            ->beforeLast('.')
            ->replace(['-', '_'], ' ')
            ->title();

        ProductAsset::create([
            'product_id' => $validated['product_id'],
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'title' => $validated['title'] ?: $fallbackTitle,
            'description' => $validated['description'],
            'alt_text' => $validated['alt_text'],
        ]);

        return back()->with('status', 'Asset uploaded. Metadata is ready to edit.');
    })->name('dashboard.assets.store');

    Route::patch('dashboard/assets/{productAsset}', function (Request $request, ProductAsset $productAsset) {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'alt_text' => ['nullable', 'string', 'max:1000'],
        ]);

        $productAsset->update($validated);

        return back()->with('status', 'Asset saved.');
    })->name('dashboard.assets.update');

    Route::get('dashboard/support-replies', function () {
        return view('dashboard.support-replies', [
            'supportMessages' => SupportMessage::latest()->get(),
        ]);
    })->name('dashboard.support-replies.index');

    Route::post('dashboard/support-replies/{supportMessage}/draft', function (SupportMessage $supportMessage) {
        if ($supportMessage->draft_reply !== null) {
            return back()->with('status', 'This email already has a draft reply.');
        }

        $messageText = Str::lower($supportMessage->subject.' '.$supportMessage->message);

        $faq = Faq::query()
            ->get()
            ->first(fn (Faq $faq) => Str::of($messageText)->contains(
                Str::of($faq->question)->lower()->explode(' ')->filter(fn (string $word) => strlen($word) > 4)->all()
            ));

        $product = Product::query()
            ->get()
            ->first(fn (Product $product) => Str::of($messageText)->contains(Str::lower($product->name)));

        $answer = $faq?->answer ?? 'I could not find an exact FAQ match yet, so I would answer with our standard friendly support tone and ask one clarifying question.';
        $productLine = $product ? " I also found the related product: {$product->name}." : '';

        $supportMessage->update([
            'draft_reply' => "Hi {$supportMessage->customer_name}, thanks for reaching out! {$answer}{$productLine} If this does not solve it, reply here and we will take a closer look.",
        ]);

        return back()->with('status', "Draft reply added to {$supportMessage->customer_name}'s email.");
    })->name('dashboard.support-replies.draft');

    Route::get('dashboard/knowledge-base', function () {
        return view('dashboard.knowledge-base', [
            'faqs' => Faq::latest()->get(),
        ]);
    })->name('dashboard.knowledge-base.index');
});

require __DIR__.'/settings.php';
