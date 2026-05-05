<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:heading size="xl">The Artisan Supply dashboard</flux:heading>
                <flux:text class="mt-2 max-w-3xl">
                    Three practical shop workflows with simple placeholder behavior. Later, each action can swap its deterministic PHP logic for Laravel AI SDK calls.
                </flux:text>
            </div>

            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <flux:badge color="red">Feature 1</flux:badge>
                        <flux:heading class="mt-3" size="lg">Asset metadata</flux:heading>
                        <flux:text class="mt-2">Upload an asset and fill title, description, size, and type from the file.</flux:text>
                    </div>
                    <div class="rounded-xl bg-red-100 p-3 text-2xl dark:bg-red-950/50">📎</div>
                </div>

                <form class="mt-5 space-y-3 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800/60" method="POST" action="{{ route('dashboard.assets.store') }}" enctype="multipart/form-data">
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

                <div class="mt-5 space-y-3">
                    @foreach ($assets as $asset)
                        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $asset->filename }}</p>
                                    <p class="mt-1 text-sm text-zinc-500">{{ $asset->product->name }} · {{ $asset->mime_type }} · {{ number_format($asset->size / 1000000, 1) }} MB</p>
                                </div>
                                <flux:badge color="lime">Filled</flux:badge>
                            </div>
                            <div class="mt-4 grid gap-3 text-sm">
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
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <flux:badge color="purple">Feature 2</flux:badge>
                        <flux:heading class="mt-3" size="lg">Product image</flux:heading>
                        <flux:text class="mt-2">Write a prompt and create a simple generated-image placeholder.</flux:text>
                    </div>
                    <div class="rounded-xl bg-purple-100 p-3 text-2xl dark:bg-purple-950/50">🖼️</div>
                </div>

                <form class="mt-5 space-y-3 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800/60" method="POST" action="{{ route('dashboard.images.store') }}">
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

                <div class="mt-5 space-y-3">
                    @foreach ($imageRequests as $request)
                        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $request->product->name }}</p>
                                <flux:badge color="amber">{{ ucfirst($request->status) }}</flux:badge>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">“{{ $request->prompt }}”</p>
                            <div class="mt-4 grid aspect-video place-items-center rounded-lg border border-dashed border-purple-300 bg-gradient-to-br from-purple-100 via-red-100 to-amber-100 p-4 text-center text-sm font-medium text-purple-900 dark:border-purple-800 dark:from-purple-950 dark:via-red-950 dark:to-amber-950 dark:text-purple-100">
                                {{ $request->image_path ? 'Generated placeholder image' : 'No generated image yet' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <flux:badge color="blue">Feature 3</flux:badge>
                        <flux:heading class="mt-3" size="lg">Support reply</flux:heading>
                        <flux:text class="mt-2">Paste a customer question and draft an answer from local FAQs/products.</flux:text>
                    </div>
                    <div class="rounded-xl bg-blue-100 p-3 text-2xl dark:bg-blue-950/50">💬</div>
                </div>

                <form class="mt-5 space-y-3 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800/60" method="POST" action="{{ route('dashboard.support-replies.store') }}">
                    @csrf
                    <input name="customer_name" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Customer name" value="Nuno from Localhost">
                    <input name="customer_email" type="email" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="customer@example.com" value="nuno@example.com">
                    <input name="subject" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Subject">
                    <textarea name="message" rows="4" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Customer question"></textarea>
                    <flux:button class="w-full" variant="primary" type="submit">Draft reply from shop data</flux:button>
                </form>

                @foreach ($supportMessages as $message)
                    <div class="mt-5 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <p class="text-sm text-zinc-500">{{ $message->customer_name }} · {{ $message->customer_email }}</p>
                        <p class="mt-2 font-semibold text-zinc-900 dark:text-zinc-100">{{ $message->subject }}</p>
                        <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">{{ $message->message }}</p>
                        <div class="mt-4 rounded-lg bg-zinc-50 p-3 text-sm dark:bg-zinc-800/70">
                            <p class="font-medium text-zinc-500">Draft reply</p>
                            <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $message->draft_reply ?? 'Nothing drafted yet' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <flux:heading size="lg">FAQ knowledge base</flux:heading>
                    <flux:text class="mt-1">This is the source data the support reply can search once AI is added.</flux:text>
                </div>
                <flux:badge>{{ $faqs->count() }} entries</flux:badge>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-3">
                @foreach ($faqs as $faq)
                    <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $faq->question }}</p>
                        <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">{{ $faq->answer }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::app>
