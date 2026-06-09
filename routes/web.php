<?php

use App\Ai\DashboardAssistant;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Ai\Image;

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

Route::post('/support/voice-messages', function (Request $request) {
    $validated = $request->validate([
        'customer_name' => ['required', 'string', 'max:120'],
        'customer_email' => ['required', 'email', 'max:255'],
        'audio' => ['required', 'file', 'max:15360', 'mimetypes:audio/webm,audio/ogg,audio/mpeg,audio/mp4,audio/wav,video/webm'],
    ]);

    $audio = $request->file('audio');
    $mimeType = $audio->getMimeType();
    $extension = $audio->guessExtension() ?: 'webm';
    $filename = Str::uuid().'.'.$extension;
    $path = 'uploads/support-audio/'.$filename;

    File::ensureDirectoryExists(public_path('uploads/support-audio'));
    $audio->move(public_path('uploads/support-audio'), $filename);

    SupportMessage::create([
        'customer_name' => $validated['customer_name'],
        'customer_email' => $validated['customer_email'],
        'subject' => 'Voice support message',
        'message' => 'Voice message submitted from the support page.',
        'audio_path' => $path,
        'audio_mime_type' => $mimeType,
        'audio_size' => filesize(public_path($path)),
    ]);

    return response()->json([
        'message' => 'Voice message received. Our support wizards are listening.',
    ]);
})->name('support.voice-messages.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard', [
            'assetCount' => ProductAsset::count(),
            'supportMessageCount' => SupportMessage::count(),
            'draftedReplyCount' => SupportMessage::query()->whereNotNull('draft_reply')->count(),
            'faqCount' => Faq::count(),
        ]);
    })->name('dashboard');

    Route::post('dashboard/assistant', function (Request $request, DashboardAssistant $assistant) {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $response = $assistant->prompt($validated['message']);
        } catch (Throwable $exception) {
            Log::warning('Dashboard assistant failed.', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'The assistant could not answer right now. Check the AI provider configuration and try again.',
            ], 500);
        }

        return response()->json([
            'message' => (string) $response,
        ]);
    })->name('dashboard.assistant');

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
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $filename = Str::uuid().'-'.$originalName;
        $file->move(public_path('uploads/assets'), $filename);
        $fallbackTitle = Str::of($originalName)
            ->beforeLast('.')
            ->replace(['-', '_'], ' ')
            ->title();

        ProductAsset::create([
            'product_id' => $validated['product_id'],
            'filename' => $originalName,
            'file_path' => 'uploads/assets/'.$filename,
            'mime_type' => $mimeType,
            'size' => $size,
            'title' => $validated['title'] ?: $fallbackTitle,
            'description' => $validated['description'],
            'alt_text' => $validated['alt_text'],
        ]);

        return back()->with('status', 'Asset uploaded. Metadata is ready to edit.');
    })->name('dashboard.assets.store');

    Route::post('dashboard/assets/generate', function (Request $request) {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'prompt' => ['required', 'string', 'max:1000'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $prompt = <<<PROMPT
Create a square product image for The Artisan Supply.
Product: {$product->name}
Tagline: {$product->tagline}
Style: playful premium Laravel merch, clean studio lighting, no text in the image.
Request: {$validated['prompt']}
PROMPT;

        $image = Image::of($prompt)
            ->square()
            ->quality('high')
            ->generate();

        $generatedImage = $image->firstImage();
        $filename = Str::uuid().'.png';
        $path = 'uploads/assets/'.$filename;

        File::ensureDirectoryExists(public_path('uploads/assets'));
        File::put(public_path($path), $generatedImage->content());

        ProductAsset::create([
            'product_id' => $product->id,
            'filename' => $filename,
            'file_path' => $path,
            'mime_type' => $generatedImage->mime ?? 'image/png',
            'size' => filesize(public_path($path)),
            'title' => $product->name.' generated image',
            'description' => 'Generated product visual for '.$product->name.'.',
            'alt_text' => $validated['prompt'],
            'prompt' => $prompt,
        ]);

        return back()->with('status', 'Image generated and saved to the asset library.');
    })->name('dashboard.assets.generate');

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
            'supportMessages' => SupportMessage::query()
                ->whereNotNull('audio_path')
                ->latest()
                ->get(),
        ]);
    })->name('dashboard.support-replies.index');

    Route::post('dashboard/support-replies/{supportMessage}/draft', function (Request $request, SupportMessage $supportMessage) {
        if ($supportMessage->draft_reply !== null) {
            return back()->with('status', 'This email already has a draft reply.');
        }

        $validated = $request->validate([
            'draft_reply' => ['required', 'string', 'max:3000'],
        ]);

        $supportMessage->update([
            'draft_reply' => $validated['draft_reply'],
        ]);

        return back()->with('status', "Draft reply added to {$supportMessage->customer_name}'s email.");
    })->name('dashboard.support-replies.draft');

    Route::get('dashboard/knowledge-base', function () {
        return view('dashboard.knowledge-base', [
            'faqs' => Faq::latest()->get(),
        ]);
    })->name('dashboard.knowledge-base.index');

    Route::post('dashboard/knowledge-base', function (Request $request) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:4000'],
        ]);

        Faq::create([
            'question' => $validated['title'],
            'answer' => $validated['text'],
        ]);

        return back()->with('status', 'Knowledge base entry added.');
    })->name('dashboard.knowledge-base.store');
});

require __DIR__.'/settings.php';
