<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __invoke(Product $product): View
    {
        return view('shop.show', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->whereKeyNot($product->id)
                ->inRandomOrder()
                ->limit(3)
                ->get(),
        ]);
    }
}
