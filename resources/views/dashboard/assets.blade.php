<x-layouts::app :title="__('Assets Manager')">
    <div class="min-h-full bg-zinc-50/70 px-4 py-6 dark:bg-zinc-950/40 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-6">
            <div class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="relative isolate px-6 py-7 sm:px-8">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,rgba(255,45,32,0.16),transparent_34%),linear-gradient(135deg,rgba(255,248,241,0.95),rgba(255,255,255,0.9))] dark:bg-[radial-gradient(circle_at_top_left,rgba(255,45,32,0.22),transparent_34%),linear-gradient(135deg,rgba(24,24,27,0.98),rgba(9,9,11,0.98))]"></div>

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <flux:badge color="red">Feature 1</flux:badge>
                            <flux:heading class="mt-3" size="xl">Assets Manager</flux:heading>
                            <flux:text class="mt-2 text-base">Upload and organize product visuals or documents. Later, generated images can land here as new assets.</flux:text>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:flex">
                            <div class="rounded-2xl border border-white/70 bg-white/75 px-4 py-3 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/70">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Assets</p>
                                <p class="mt-1 text-2xl font-black text-zinc-950 dark:text-white">{{ $assets->count() }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/70 bg-white/75 px-4 py-3 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/70">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Products</p>
                                <p class="mt-1 text-2xl font-black text-zinc-950 dark:text-white">{{ $products->count() }}</p>
                            </div>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[22rem_22rem_1fr]">
                <form class="h-fit overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.assets.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="border-b border-zinc-100 bg-zinc-50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-900/80">
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-red-500">New upload</p>
                        <p class="mt-1 text-sm text-zinc-500">Drop in one asset and fill the fields manually.</p>
                    </div>

                    <div class="space-y-4 p-5">
                        <div x-data="{ fileName: null }">
                            <label class="group flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-red-200 bg-red-50/60 px-5 py-8 text-center transition hover:border-red-300 hover:bg-red-50 dark:border-red-950 dark:bg-red-950/20" for="asset">
                                <div class="rounded-2xl bg-white p-3 text-2xl shadow-sm dark:bg-zinc-900">📎</div>
                                <p class="mt-3 text-sm font-semibold text-zinc-900 dark:text-zinc-100">Upload asset</p>
                                <p class="mt-1 text-xs text-zinc-500">Type and size are captured automatically</p>
                                <input id="asset" name="asset" type="file" class="sr-only" x-on:change="fileName = $event.target.files[0]?.name ?? null">
                            </label>

                            <div x-show="fileName" x-cloak class="mt-3 rounded-xl border border-red-100 bg-white px-3 py-2 text-sm font-medium text-zinc-700 shadow-sm dark:border-red-950 dark:bg-zinc-900 dark:text-zinc-200">
                                <span class="text-zinc-400">Selected:</span>
                                <span x-text="fileName"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="asset_product_id">Product</label>
                            <select id="asset_product_id" name="product_id" class="mt-2 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="title">Title</label>
                            <input id="title" name="title" class="mt-2 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Hero product photo">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="description">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-2 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Where this asset should be used"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="alt_text">Image alt text</label>
                            <textarea id="alt_text" name="alt_text" rows="2" class="mt-2 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Describe the image for accessibility"></textarea>
                        </div>

                        <flux:button class="w-full" variant="primary" type="submit">Upload asset</flux:button>
                    </div>
                </form>

                <form class="h-fit overflow-hidden rounded-[1.75rem] border border-red-200 bg-red-50/70 shadow-sm dark:border-red-950 dark:bg-red-950/20" method="POST" action="{{ route('dashboard.assets.generate') }}">
                    @csrf
                    <div class="border-b border-red-100 bg-white/70 px-5 py-4 dark:border-red-950 dark:bg-zinc-900/70">
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-red-500">Generate image</p>
                        <p class="mt-1 text-sm text-zinc-500">Describe the missing product visual and save it as an asset.</p>
                    </div>

                    <div class="space-y-4 p-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="generated_product_id">Product</label>
                            <select id="generated_product_id" name="product_id" class="mt-2 w-full rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm dark:border-red-950 dark:bg-zinc-900">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="prompt">Prompt</label>
                            <textarea id="prompt" name="prompt" rows="4" class="mt-2 w-full rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm dark:border-red-950 dark:bg-zinc-900" placeholder="A cozy studio photo of the Queue Worker Lunchbox beside a laptop..."></textarea>
                        </div>

                        <flux:button class="w-full" variant="primary" type="submit">Generate product image</flux:button>
                    </div>
                </form>

                <div>
                    <div class="mb-4 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-zinc-950 dark:text-white">Uploaded assets</p>
                            <p class="mt-1 text-sm text-zinc-500">A clean gallery. Hover or open an asset to see the metadata.</p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                        @forelse ($assets as $asset)
                            @php($isImage = str($asset->mime_type)->contains('image'))

                            <details class="group/asset overflow-hidden rounded-[1.6rem] border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg open:sm:col-span-2 dark:border-zinc-800 dark:bg-zinc-900">
                                <summary class="relative block aspect-square cursor-pointer list-none overflow-hidden [&::-webkit-details-marker]:hidden">
                                    @if ($isImage && $asset->file_path)
                                        <img src="{{ asset($asset->file_path) }}" alt="{{ $asset->alt_text ?: $asset->title }}" class="h-full w-full object-cover transition duration-500 group-hover/asset:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/70 via-zinc-950/10 to-transparent"></div>
                                    @elseif ($isImage)
                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-red-50 via-orange-50 to-white dark:from-red-950/30 dark:via-zinc-900 dark:to-zinc-950">
                                            <div class="flex h-20 w-20 items-center justify-center rounded-[1.6rem] bg-white text-4xl shadow-sm dark:bg-zinc-900">🖼️</div>
                                        </div>
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-zinc-50 via-white to-zinc-100 dark:from-zinc-800 dark:via-zinc-900 dark:to-zinc-950">
                                            <div class="text-center">
                                                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.6rem] bg-white text-4xl shadow-sm dark:bg-zinc-900">📄</div>
                                                <p class="mt-4 text-xs font-bold uppercase tracking-[0.22em] text-zinc-400">{{ str($asset->mime_type ?? 'file')->after('/') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="absolute inset-x-0 bottom-0 p-4">
                                        <div class="rounded-2xl bg-white/88 p-3 shadow-sm backdrop-blur dark:bg-zinc-950/82">
                                            <p class="truncate font-black leading-tight text-zinc-950 dark:text-white">{{ $asset->title ?: $asset->filename }}</p>
                                            <div class="mt-2 flex items-center justify-between gap-3 text-xs font-semibold text-zinc-500">
                                                <span class="truncate">{{ $asset->product?->name }}</span>
                                                <span>{{ number_format($asset->size / 1000000, 1) }} MB</span>
                                            </div>
                                        </div>
                                    </div>
                                </summary>

                                <div class="space-y-4 border-t border-zinc-100 p-4 dark:border-zinc-800">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{{ $asset->mime_type ?? 'Unknown' }}</span>
                                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{{ $asset->filename }}</span>
                                    </div>

                                    @if ($asset->description)
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-zinc-400">Description</p>
                                            <p class="mt-2 text-sm leading-6 text-zinc-700 dark:text-zinc-200">{{ $asset->description }}</p>
                                        </div>
                                    @endif

                                    @if ($isImage && $asset->alt_text)
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-zinc-400">Alt text</p>
                                            <p class="mt-2 text-sm leading-6 text-zinc-700 dark:text-zinc-200">{{ $asset->alt_text }}</p>
                                        </div>
                                    @endif

                                    @if ($asset->prompt)
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-zinc-400">Prompt</p>
                                            <p class="mt-2 text-sm leading-6 text-zinc-700 dark:text-zinc-200">{{ $asset->prompt }}</p>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        @empty
                            <div class="rounded-[1.75rem] border border-dashed border-zinc-300 bg-white p-10 text-center text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 sm:col-span-2 lg:col-span-3 2xl:col-span-4">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-xl dark:bg-red-950/30">📎</div>
                                <p class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">No uploaded assets yet.</p>
                                <p class="mt-1 text-sm">Upload the first file to start building the demo library.</p>
                            </div>
                        @endforelse
                    </div>
                </div>            </div>
        </div>
    </div>
</x-layouts::app>
