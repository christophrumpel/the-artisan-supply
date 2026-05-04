<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateProductAssetController extends Controller
{
    public function __invoke(Request $request, ProductAsset $productAsset): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'alt_text' => ['nullable', 'string', 'max:1000'],
        ]);

        $productAsset->update($validated);

        return back()->with('status', 'Asset saved.');
    }
}
