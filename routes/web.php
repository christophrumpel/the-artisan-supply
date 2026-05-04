<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\GenerateSupportReplyController;
use App\Http\Controllers\Dashboard\KnowledgeBaseIndexController;
use App\Http\Controllers\Dashboard\ProductAssetIndexController;
use App\Http\Controllers\Dashboard\StoreKnowledgeBaseEntryController;
use App\Http\Controllers\Dashboard\StoreProductAssetController;
use App\Http\Controllers\Dashboard\StoreSupportDraftReplyController;
use App\Http\Controllers\Dashboard\SupportReplyIndexController;
use App\Http\Controllers\Dashboard\UpdateProductAssetController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\SupportController;
use App\Http\Controllers\SupportVoiceMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/products/{product:slug}', ProductController::class)->name('products.show');

Route::redirect('/studio', '/dashboard')->name('studio');

Route::get('/support', SupportController::class)->name('support');

Route::post('/support/voice-messages', SupportVoiceMessageController::class)
    ->name('support.voice-messages.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('dashboard/assets', ProductAssetIndexController::class)->name('dashboard.assets.index');
    Route::post('dashboard/assets', StoreProductAssetController::class)->name('dashboard.assets.store');
    Route::patch('dashboard/assets/{productAsset}', UpdateProductAssetController::class)->name('dashboard.assets.update');

    Route::get('dashboard/support-replies', SupportReplyIndexController::class)->name('dashboard.support-replies.index');
    Route::post('dashboard/support-replies/{supportMessage}/draft', StoreSupportDraftReplyController::class)
        ->name('dashboard.support-replies.draft');

    Route::post('dashboard/support-replies/{supportMessage}/generate', GenerateSupportReplyController::class)
        ->name('dashboard.support-replies.generate');

    Route::get('dashboard/knowledge-base', KnowledgeBaseIndexController::class)->name('dashboard.knowledge-base.index');
    Route::post('dashboard/knowledge-base', StoreKnowledgeBaseEntryController::class)
        ->name('dashboard.knowledge-base.store');
});

require __DIR__.'/settings.php';
