<?php

use App\Models\Faq;
use App\Models\ImageRequest;
use App\Models\Product;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;

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
});

test('authenticated users can visit the dashboard', function () {
    $user = dashboardShopkeeper();
    dashboardProduct();

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('The Artisan Supply dashboard');
    $response->assertSee('Asset metadata');
    $response->assertSee('Product image');
    $response->assertSee('Support reply');
});

test('shopkeepers can analyze an uploaded asset with placeholder metadata', function () {
    $user = dashboardShopkeeper();
    $product = dashboardProduct();

    $this->actingAs($user)
        ->post(route('dashboard.assets.store'), [
            'product_id' => $product->id,
            'asset' => UploadedFile::fake()->image('hero-shot.png', 800, 600),
        ])
        ->assertRedirect();

    $asset = ProductAsset::first();

    expect($asset)
        ->filename->toBe('hero-shot.png')
        ->title->toBe('Hero Shot')
        ->description->toContain('Queue Worker Lunchbox');
});

test('shopkeepers can create a generated image placeholder', function () {
    $user = dashboardShopkeeper();
    $product = dashboardProduct();

    $this->actingAs($user)
        ->post(route('dashboard.images.store'), [
            'product_id' => $product->id,
            'prompt' => 'A warm product photo on a developer desk.',
        ])
        ->assertRedirect();

    expect(ImageRequest::first())
        ->prompt->toBe('A warm product photo on a developer desk.')
        ->status->toBe('generated placeholder')
        ->image_path->toBe('placeholder');
});

test('shopkeepers can draft a support reply from local shop data', function () {
    $user = dashboardShopkeeper();
    dashboardProduct();

    Faq::create([
        'question' => 'When will my Queue Worker Lunchbox ship?',
        'answer' => 'Most orders ship within 2-3 business days unless they enter the failed jobs table.',
    ]);

    $this->actingAs($user)
        ->post(route('dashboard.support-replies.store'), [
            'customer_name' => 'Nuno',
            'customer_email' => 'nuno@example.com',
            'subject' => 'Queue Worker Lunchbox shipping',
            'message' => 'When will my lunchbox ship?',
        ])
        ->assertRedirect();

    expect(SupportMessage::first())
        ->draft_reply->toContain('Most orders ship within 2-3 business days')
        ->draft_reply->toContain('Queue Worker Lunchbox');
});
