<?php

use App\Models\Faq;
use App\Models\ImageRequest;
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
            'products' => Product::query()->orderByDesc('featured')->orderBy('name')->get(),
            'assets' => ProductAsset::with('product')->latest()->get(),
            'imageRequests' => ImageRequest::with('product')->latest()->get(),
            'supportMessages' => SupportMessage::latest()->get(),
            'faqs' => Faq::latest()->get(),
        ]);
    })->name('dashboard');

    Route::post('dashboard/assets', function (Request $request) {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'asset' => ['required', 'file', 'max:10240'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $file = $request->file('asset');
        $title = Str::of($file->getClientOriginalName())
            ->beforeLast('.')
            ->replace(['-', '_'], ' ')
            ->title();

        ProductAsset::create([
            'product_id' => $product->id,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'title' => $title,
            'description' => "A {$file->getMimeType()} asset for {$product->name}. Placeholder metadata generated from the upload until the AI analyzer is wired in.",
        ]);

        return back()->with('status', 'Asset metadata filled from the uploaded file.');
    })->name('dashboard.assets.store');

    Route::post('dashboard/images', function (Request $request) {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'prompt' => ['required', 'string', 'max:500'],
        ]);

        ImageRequest::create([
            'product_id' => $validated['product_id'],
            'prompt' => $validated['prompt'],
            'status' => 'generated placeholder',
            'image_path' => 'placeholder',
        ]);

        return back()->with('status', 'Placeholder product image generated.');
    })->name('dashboard.images.store');

    Route::post('dashboard/support-replies', function (Request $request) {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $messageText = Str::lower($validated['subject'].' '.$validated['message']);

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

        SupportMessage::create($validated + [
            'draft_reply' => "Hi {$validated['customer_name']}, thanks for reaching out! {$answer}{$productLine} If this does not solve it, reply here and we will take a closer look.",
        ]);

        return back()->with('status', 'Support reply drafted from the local FAQ and product data.');
    })->name('dashboard.support-replies.store');
});

require __DIR__.'/settings.php';
