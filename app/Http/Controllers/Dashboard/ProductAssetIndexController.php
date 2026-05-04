<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAsset;
use Illuminate\View\View;

class ProductAssetIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard.assets', [
            'products' => Product::query()->orderByDesc('featured')->orderBy('name')->get(),
            'assets' => ProductAsset::with('product')->latest()->get(),
        ]);
    }
}
