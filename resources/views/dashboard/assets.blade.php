<x-layouts::app :title="__('Asset metadata')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:badge color="red">Feature 1</flux:badge>
                <flux:heading class="mt-3" size="xl">Asset metadata</flux:heading>
                <flux:text class="mt-2 max-w-3xl">Upload an asset and fill title, description, size, and type from the file.</flux:text>
            </div>

            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="grid gap-6 xl:grid-cols-[24rem_1fr]">
            <form class="space-y-3 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.assets.store') }}" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset_product_id">Product</label>
                <select id="asset_product_id" name="product_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>

                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="asset">Asset file</label>
                <input id="asset" name="asset" type="file" class="w-full rounded-lg border border-dashed border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">

                <flux:button class="w-full" variant="primary" type="submit">Analyze uploaded asset</flux:button>
            </form>

            <div class="space-y-3">
                @forelse ($assets as $asset)
                    <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $asset->filename }}</p>
                                <p class="mt-1 text-sm text-zinc-500">{{ $asset->product->name }} · {{ $asset->mime_type }} · {{ number_format($asset->size / 1000000, 1) }} MB</p>
                            </div>
                            <flux:badge color="lime">Filled</flux:badge>
                        </div>
                        <div class="mt-4 grid gap-3 text-sm md:grid-cols-2">
                            <div>
                                <p class="font-medium text-zinc-500">Title</p>
                                <p class="text-zinc-900 dark:text-zinc-100">{{ $asset->title ?? 'Not written yet' }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-zinc-500">Description</p>
                                <p class="text-zinc-900 dark:text-zinc-100">{{ $asset->description ?? 'Not written yet' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-zinc-300 p-8 text-center text-zinc-500 dark:border-zinc-700">No uploaded assets yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
