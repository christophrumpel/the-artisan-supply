<x-shop-layout title="The Artisan Supply">
    <section class="mx-auto grid max-w-7xl items-center gap-12 px-6 pb-16 pt-12 lg:grid-cols-[1.1fr_.9fr] lg:px-8 lg:pt-20">
        <div>
            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-red-300/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-100">
                <span>New drop</span>
                <span class="h-1 w-1 rounded-full bg-red-300"></span>
                <span>Tools for highly dramatic developers</span>
            </div>
            <h1 class="max-w-4xl text-5xl font-black tracking-tight text-white sm:text-7xl">
                Laravel merch from an alternate timeline.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">
                A tiny shop for imaginary developer goods: artisan wands, queue lunchboxes, migration time machines, and other things the official store wisely refuses to sell.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="#products" class="rounded-full bg-red-500 px-6 py-3 font-bold text-white shadow-xl shadow-red-950/40 transition hover:-translate-y-0.5 hover:bg-red-400">Browse the shelf</a>
                <a href="{{ route('support') }}" class="rounded-full border border-white/15 bg-white/5 px-6 py-3 font-bold text-white transition hover:bg-white/10">Visit support desk</a>
            </div>
        </div>

        <div class="relative">
            <div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-red-500/20 via-amber-400/10 to-purple-500/20 blur-2xl"></div>
            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.06] p-6 shadow-2xl shadow-black/40 backdrop-blur">
                <div class="mb-6 flex items-center justify-between">
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-stone-400">Featured crate</p>
                    <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-sm font-semibold text-emerald-200">In stock-ish</span>
                </div>
                <div class="grid gap-4">
                    @foreach ($featuredProducts->take(3) as $product)
                        <a href="{{ route('products.show', $product) }}" class="group flex items-center gap-4 rounded-3xl border border-white/10 bg-[#1f1110]/80 p-4 transition hover:-translate-y-1 hover:border-red-300/30 hover:bg-[#2a1513]">
                            <span class="grid h-16 w-16 shrink-0 place-items-center rounded-2xl bg-gradient-to-br {{ $product->color }} text-3xl shadow-lg shadow-black/30">{{ $product->emoji }}</span>
                            <span class="min-w-0">
                                <span class="block font-bold text-white group-hover:text-red-100">{{ $product->name }}</span>
                                <span class="mt-1 block text-sm text-stone-400">{{ $product->tagline }}</span>
                            </span>
                            <span class="ml-auto font-bold text-red-100">{{ $product->price() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-red-200">Catalog</p>
                <h2 class="mt-2 text-3xl font-black text-white">Artifacts, supplies, questionable objects</h2>
            </div>
            <p class="max-w-lg text-sm leading-6 text-stone-400">This is intentionally simple for the video: a real-looking shop surface we can later enhance with AI asset metadata, generated product imagery, and support replies.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product) }}" class="group overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/[0.045] shadow-xl shadow-black/20 backdrop-blur transition hover:-translate-y-1 hover:border-red-300/30 hover:bg-white/[0.07]">
                    <div class="relative h-48 bg-gradient-to-br {{ $product->color }} p-5">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,.35),transparent_30%),linear-gradient(135deg,rgba(0,0,0,.05),rgba(0,0,0,.45))]"></div>
                        @if ($product->badge)
                            <span class="relative rounded-full bg-black/25 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white backdrop-blur">{{ $product->badge }}</span>
                        @endif
                        <div class="absolute bottom-5 right-6 text-7xl drop-shadow-2xl transition group-hover:scale-110 group-hover:rotate-3">{{ $product->emoji }}</div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="text-xl font-black text-white">{{ $product->name }}</h3>
                            <span class="font-bold text-red-100">{{ $product->price() }}</span>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-red-100/80">{{ $product->tagline }}</p>
                        <p class="mt-4 line-clamp-3 text-sm leading-6 text-stone-400">{{ $product->description }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</x-shop-layout>
