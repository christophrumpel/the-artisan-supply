<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StoreProductAssetController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'asset' => ['required', 'file', 'max:10240'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'alt_text' => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $request->file('asset');
        $originalName = $file->getClientOriginalName();
        $size = $file->getSize();
        $filename = Str::uuid().'-'.$originalName;

        File::ensureDirectoryExists(public_path('uploads/assets'));
        $file->move(public_path('uploads/assets'), $filename);

        ProductAsset::create([
            'product_id' => $validated['product_id'],
            'filename' => $originalName,
            'file_path' => 'uploads/assets/'.$filename,
            'mime_type' => $file->getMimeType(),
            'size' => $size,
            'title' => ($validated['title'] ?? null) ?: Str::of($originalName)->beforeLast('.')->replace(['-', '_'], ' ')->title(),
            'description' => $validated['description'] ?? null,
            'alt_text' => $validated['alt_text'] ?? null,
        ]);

        return back()->with('status', 'Asset uploaded. Metadata is ready to edit.');
    }
}
