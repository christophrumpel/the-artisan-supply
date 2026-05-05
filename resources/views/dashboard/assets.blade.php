<x-layouts::app :title="__('Assets Manager')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:badge color="red">Feature 1</flux:badge>
                <flux:heading class="mt-3" size="xl">Assets Manager</flux:heading>
                <flux:text class="mt-2 max-w-3xl">Upload shop assets now, then manually maintain the metadata AI will fill later: title, description, and alt text for images.</flux:text>
            </div>

            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="grid gap-6 xl:grid-cols-[24rem_1fr]">
            <form class="space-y-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.assets.store') }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset_product_id">Product</label>
                    <select id="asset_product_id" name="product_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset">Asset file</label>
                    <input id="asset" name="asset" type="file" class="mt-1 w-full rounded-lg border border-dashed border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="mt-1 text-xs text-zinc-500">File type and size are captured automatically.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="title">Title</label>
                    <input id="title" name="title" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Hero product photo">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="description">Description</label>
                    <textarea id="description" name="description" rows="3" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Where this asset should be used"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="alt_text">Image alt text</label>
                    <textarea id="alt_text" name="alt_text" rows="2" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Describe the image for accessibility"></textarea>
                </div>

                <flux:button class="w-full" variant="primary" type="submit">Upload asset</flux:button>
            </form>

            <div class="space-y-4">
                @forelse ($assets as $asset)
                    <form class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.assets.update', $asset) }}">
                        @csrf
                        @method('PATCH')

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $asset->filename }}</p>
                                <p class="mt-1 text-sm text-zinc-500">{{ $asset->mime_type ?? 'Unknown type' }} · {{ number_format($asset->size / 1000000, 1) }} MB</p>
                            </div>
                            <flux:badge color="lime">Editable</flux:badge>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset_{{ $asset->id }}_product_id">Product</label>
                                <select id="asset_{{ $asset->id }}_product_id" name="product_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected($asset->product_id === $product->id)>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset_{{ $asset->id }}_title">Title</label>
                                <input id="asset_{{ $asset->id }}_title" name="title" value="{{ $asset->title }}" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset_{{ $asset->id }}_description">Description</label>
                                <textarea id="asset_{{ $asset->id }}_description" name="description" rows="3" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">{{ $asset->description }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset_{{ $asset->id }}_alt_text">Image alt text</label>
                                <textarea id="asset_{{ $asset->id }}_alt_text" name="alt_text" rows="2" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">{{ $asset->alt_text }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <flux:button variant="primary" type="submit">Save metadata</flux:button>
                        </div>
                    </form>
                @empty
                    <div class="rounded-xl border border-dashed border-zinc-300 p-8 text-center text-zinc-500 dark:border-zinc-700">No uploaded assets yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
