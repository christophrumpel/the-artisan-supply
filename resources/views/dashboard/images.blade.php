<x-layouts::app :title="__('Product images')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:badge color="purple">Feature 2</flux:badge>
                <flux:heading class="mt-3" size="xl">Product images</flux:heading>
                <flux:text class="mt-2 max-w-3xl">Write a prompt and create a simple generated-image placeholder.</flux:text>
            </div>

            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="grid gap-6 xl:grid-cols-[24rem_1fr]">
            <form class="space-y-3 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.images.store') }}">
                @csrf
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="image_product_id">Product</label>
                <select id="image_product_id" name="product_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>

                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200" for="prompt">Prompt</label>
                <textarea id="prompt" name="prompt" rows="3" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="A premium product photo on a cozy developer desk"></textarea>

                <flux:button class="w-full" variant="primary" type="submit">Generate placeholder image</flux:button>
            </form>

            <div class="grid gap-4 md:grid-cols-2">
                @forelse ($imageRequests as $request)
                    <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex items-start justify-between gap-3">
                            <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $request->product->name }}</p>
                            <flux:badge color="amber">{{ ucfirst($request->status) }}</flux:badge>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">“{{ $request->prompt }}”</p>
                        <div class="mt-4 grid aspect-video place-items-center rounded-lg border border-dashed border-purple-300 bg-gradient-to-br from-purple-100 via-red-100 to-amber-100 p-4 text-center text-sm font-medium text-purple-900 dark:border-purple-800 dark:from-purple-950 dark:via-red-950 dark:to-amber-950 dark:text-purple-100">
                            {{ $request->image_path ? 'Generated placeholder image' : 'No generated image yet' }}
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-zinc-300 p-8 text-center text-zinc-500 dark:border-zinc-700 md:col-span-2">No image requests yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
