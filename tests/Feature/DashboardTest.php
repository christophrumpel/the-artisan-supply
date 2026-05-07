<?php

use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Transcription;

function dashboardShopkeeper(): User
{
    return User::factory()->create();
}

function dashboardProduct(array $attributes = []): Product
{
    return Product::create($attributes + [
        'name' => 'Queue Worker Lunchbox',
        'slug' => 'queue-worker-lunchbox',
        'tagline' => 'Keeps snacks warm while jobs retry.',
        'description' => 'A durable lunchbox with three compartments: pending, processing, and failed.',
        'price_cents' => 4200,
        'badge' => 'Ships async',
        'emoji' => '🍱',
        'image_path' => 'images/products/queue-worker-lunchbox.png',
        'color' => 'from-amber-400 to-pink-500',
        'inventory' => 8,
        'featured' => true,
    ]);
}

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));

    $this->get(route('dashboard.assets.index'))->assertRedirect(route('login'));
    $this->get(route('dashboard.support-replies.index'))->assertRedirect(route('login'));
    $this->get(route('dashboard.knowledge-base.index'))->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard overview', function () {
    $user = dashboardShopkeeper();
    dashboardProduct();

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('The Artisan Supply dashboard');
    $response->assertSee('The Artisan Supply dashboard');
    $response->assertDontSee('Product images');
    $response->assertDontSee('Generate placeholder image');
});

test('authenticated users can visit the assets manager page', function () {
    $user = dashboardShopkeeper();
    dashboardProduct();

    $response = $this->actingAs($user)->get(route('dashboard.assets.index'));

    $response->assertOk();
    $response->assertSee('Assets Manager');
    $response->assertSee('Upload asset');
    $response->assertSee('Image alt text');
});

test('the separate product images page is removed', function () {
    $user = dashboardShopkeeper();

    $this->actingAs($user)->get('/dashboard/images')->assertNotFound();
});

test('authenticated users only see voice messages on the support replies page', function () {
    $user = dashboardShopkeeper();

    Faq::create([
        'question' => 'When will my Queue Worker Lunchbox ship?',
        'answer' => 'Most orders ship within 2-3 business days unless they enter the failed jobs table.',
    ]);

    SupportMessage::create([
        'customer_name' => 'Nuno from Localhost',
        'customer_email' => 'nuno@example.com',
        'subject' => 'My lunchbox has been processing forever',
        'message' => 'Is this expected or did it get stuck in a queue?',
    ]);

    SupportMessage::create([
        'customer_name' => 'Mina from Production',
        'customer_email' => 'mina@example.com',
        'subject' => 'Voice support message',
        'message' => 'Voice message submitted from the support page.',
        'audio_path' => 'uploads/support-audio/demo.webm',
        'audio_mime_type' => 'audio/webm',
        'audio_size' => 2048,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.support-replies.index'));

    $response->assertOk();
    $response->assertSee('Support replies');
    $response->assertSee('Mina from Production');
    $response->assertSee('Recorded audio');
    $response->assertDontSee('Nuno from Localhost');
    $response->assertDontSee('Most orders ship within 2-3 business days');
});

test('authenticated users can visit the knowledge base page', function () {
    $user = dashboardShopkeeper();

    Faq::create([
        'question' => 'When will my Queue Worker Lunchbox ship?',
        'answer' => 'Most orders ship within 2-3 business days unless they enter the failed jobs table.',
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.knowledge-base.index'));

    $response->assertOk();
    $response->assertSee('Knowledge base');
    $response->assertSee('New entry');
    $response->assertSee('Most orders ship within 2-3 business days');
});

test('shopkeepers can manually add a knowledge base entry', function () {
    $user = dashboardShopkeeper();

    $this->actingAs($user)
        ->post(route('dashboard.knowledge-base.store'), [
            'title' => 'Artisan Wand manual',
            'text' => 'The wand is decorative and should be cleaned with a dry cloth.',
        ])
        ->assertRedirect();

    expect(Faq::first())
        ->question->toBe('Artisan Wand manual')
        ->answer->toBe('The wand is decorative and should be cleaned with a dry cloth.');
});

test('shopkeepers can upload an asset with manual metadata', function () {
    $user = dashboardShopkeeper();
    $product = dashboardProduct();

    $this->actingAs($user)
        ->post(route('dashboard.assets.store'), [
            'product_id' => $product->id,
            'asset' => UploadedFile::fake()->image('hero-shot.png', 800, 600),
            'title' => 'Lunchbox hero shot',
            'description' => 'Primary campaign image for the lunchbox.',
            'alt_text' => 'A Queue Worker Lunchbox on a developer desk.',
        ])
        ->assertRedirect();

    $asset = ProductAsset::first();

    expect($asset)
        ->filename->toBe('hero-shot.png')
        ->file_path->toStartWith('uploads/assets/')
        ->title->toBe('Lunchbox hero shot')
        ->description->toBe('Primary campaign image for the lunchbox.')
        ->alt_text->toBe('A Queue Worker Lunchbox on a developer desk.')
        ->mime_type->toBe('image/png')
        ->size->toBeGreaterThan(0);
});

test('shopkeepers can edit asset metadata', function () {
    $user = dashboardShopkeeper();
    $product = dashboardProduct();
    $asset = ProductAsset::create([
        'product_id' => $product->id,
        'filename' => 'hero-shot.png',
        'file_path' => 'uploads/assets/hero-shot.png',
        'mime_type' => 'image/png',
        'size' => 12345,
        'title' => 'Old title',
    ]);

    $this->actingAs($user)
        ->patch(route('dashboard.assets.update', $asset), [
            'product_id' => $product->id,
            'title' => 'Updated hero shot',
            'description' => 'Updated description.',
            'alt_text' => 'Updated image alt text.',
        ])
        ->assertRedirect();

    expect($asset->refresh())
        ->title->toBe('Updated hero shot')
        ->description->toBe('Updated description.')
        ->alt_text->toBe('Updated image alt text.');
});

test('shopkeepers can draft a support reply directly on an incoming email once', function () {
    $user = dashboardShopkeeper();
    dashboardProduct();

    $message = SupportMessage::create([
        'customer_name' => 'Nuno',
        'customer_email' => 'nuno@example.com',
        'subject' => 'Queue Worker Lunchbox shipping',
        'message' => 'When will my lunchbox ship?',
    ]);

    $this->actingAs($user)
        ->post(route('dashboard.support-replies.draft', $message), [
            'draft_reply' => 'Hi Nuno, your lunchbox ships within 2-3 business days.',
        ])
        ->assertRedirect();

    $firstDraft = $message->refresh()->draft_reply;

    expect($firstDraft)->toBe('Hi Nuno, your lunchbox ships within 2-3 business days.');

    $this->actingAs($user)
        ->post(route('dashboard.support-replies.draft', $message), [
            'draft_reply' => 'This second draft should not replace the original.',
        ])
        ->assertRedirect();

    expect($message->refresh()->draft_reply)->toBe($firstDraft);
});

test('customers can submit a voice support message', function () {
    Transcription::fake(['I would like to know when my lunchbox ships.']);

    File::deleteDirectory(public_path('uploads/support-audio'));
    Storage::deleteDirectory('support-audio');

    $response = $this->postJson(route('support.voice-messages.store'), [
        'customer_name' => 'Mina from Production',
        'customer_email' => 'mina@example.com',
        'audio' => UploadedFile::fake()->createWithContent('question.webm', 'fake audio bytes'),
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('message', 'Voice message received. Our support wizards are listening.');

    $message = SupportMessage::first();

    expect($message)
        ->customer_name->toBe('Mina from Production')
        ->customer_email->toBe('mina@example.com')
        ->subject->toBe('Voice support message')
        ->message->toBe('Voice message submitted from the support page.')
        ->audio_path->toStartWith('uploads/support-audio/')
        ->audio_mime_type->toBeIn(['audio/webm', 'video/webm'])
        ->audio_size->toBeGreaterThan(0)
        ->transcription->toBe('I would like to know when my lunchbox ships.')
        ->transcribed_at->not->toBeNull();

    $this->assertFileExists(public_path($message->audio_path));
    Storage::assertExists('support-audio/'.basename($message->audio_path));

    Transcription::assertGenerated(fn ($prompt) => $prompt->audio->path === 'support-audio/'.basename($message->audio_path));
});

test('shopkeepers can play voice support messages in the dashboard', function () {
    $user = dashboardShopkeeper();

    SupportMessage::create([
        'customer_name' => 'Mina from Production',
        'customer_email' => 'mina@example.com',
        'subject' => 'Lunchbox question',
        'message' => 'Voice message submitted from the support page.',
        'audio_path' => 'uploads/support-audio/demo.webm',
        'audio_mime_type' => 'audio/webm',
        'audio_size' => 2048,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.support-replies.index'));

    $response->assertOk();
    $response->assertSee('Recorded audio');
    $response->assertSee('uploads/support-audio/demo.webm');
    $response->assertSee('2.0 KB');
});

test('the support page shows the simplified voice recorder', function () {
    $response = $this->get(route('support'));

    $response->assertOk();
    $response->assertSee('Record a message for the support team.');
    $response->assertSee('Name');
    $response->assertSee('Email');
    $response->assertSee('Start recording');
    $response->assertDontSee('Subject');
    $response->assertDontSee('Short note');
    $response->assertDontSee('Send voice message');
});

test('shopkeepers can see the transcription under the audio', function () {
    $user = dashboardShopkeeper();

    SupportMessage::create([
        'customer_name' => 'Mina from Production',
        'customer_email' => 'mina@example.com',
        'subject' => 'Voice support message',
        'message' => 'Voice message submitted from the support page.',
        'audio_path' => 'uploads/support-audio/demo.webm',
        'audio_mime_type' => 'audio/webm',
        'audio_size' => 2048,
        'transcription' => 'I would like to know when my lunchbox ships.',
        'transcribed_at' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.support-replies.index'));

    $response->assertOk();
    $response->assertSee('Transcription');
    $response->assertSee('I would like to know when my lunchbox ships.');
    $response->assertDontSee('Generate transcription');
});
