<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('shop.index', [
            'featuredProducts' => Product::query()->where('featured', true)->get(),
            'products' => Product::query()->orderByDesc('featured')->orderBy('name')->limit(4)->get(),
        ]);
    }
}
