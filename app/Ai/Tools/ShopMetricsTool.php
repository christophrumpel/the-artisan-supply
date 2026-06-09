<?php

namespace App\Ai\Tools;

use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ShopMetricsTool implements Tool
{
    public function name(): string
    {
        return 'shop_metrics';
    }

    public function description(): Stringable|string
    {
        return 'Get current read-only dashboard metrics for The Artisan Supply, including product, support message, FAQ, asset, draft reply, and inventory counts.';
    }

    public function handle(Request $request): Stringable|string
    {
        $supportMessages = SupportMessage::query();

        return collect([
            'products' => Product::count(),
            'featured_products' => Product::query()->where('featured', true)->count(),
            'total_inventory' => Product::query()->sum('inventory'),
            'product_assets' => ProductAsset::count(),
            'support_messages' => (clone $supportMessages)->count(),
            'voice_support_messages' => SupportMessage::query()->whereNotNull('audio_path')->count(),
            'support_messages_without_draft_reply' => SupportMessage::query()->whereNull('draft_reply')->count(),
            'drafted_support_replies' => SupportMessage::query()->whereNotNull('draft_reply')->count(),
            'faq_entries' => Faq::count(),
        ])->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
