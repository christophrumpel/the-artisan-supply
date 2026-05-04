<x-shop-layout title="{{ $product->name }} | The Artisan Supply">
    <section class="mx-auto grid max-w-7xl gap-10 px-6 py-12 lg:grid-cols-[.95fr_1.05fr] lg:px-8 lg:py-20">
        <div class="relative">
            <div class="absolute -inset-5 rounded-[3rem] bg-gradient-to-br {{ $product->color }} opacity-30 blur-3xl"></div>
            <div class="relative grid aspect-square place-items-center overflow-hidden rounded-[2.5rem] border border-white/10 bg-gradient-to-br {{ $product->color }} shadow-2xl shadow-black/40">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_20%,rgba(255,255,255,.35),transparent_30%),linear-gradient(135deg,rgba(255,255,255,.04),rgba(0,0,0,.55))]"></div>
                <div class="absolute left-6 top-6 rounded-full bg-black/25 px-4 py-2 text-sm font-bold uppercase tracking-widest text-white backdrop-blur">{{ $product->badge ?? 'Artifact' }}</div>
                <div class="relative text-[12rem] drop-shadow-2xl">{{ $product->emoji }}</div>
            </div>
        </div>

        <div class="self-center">
            <a href="{{ route('home') }}" class="text-sm font-bold text-red-200 hover:text-red-100">← Back to catalog</a>
            <h1 class="mt-6 text-5xl font-black tracking-tight text-white sm:text-6xl">{{ $product->name }}</h1>
            <p class="mt-4 text-2xl font-serif italic text-red-100">{{ $product->tagline }}</p>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">{{ $product->description }}</p>

            <div class="mt-8 grid gap-3 sm:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/[0.05] p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-500">Price</p>
                    <p class="mt-1 text-2xl font-black text-white">{{ $product->price() }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/[0.05] p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-500">Inventory</p>
                    <p class="mt-1 text-2xl font-black text-white">{{ $product->inventory }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/[0.05] p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-500">Status</p>
                    <p class="mt-1 text-2xl font-black text-white">Demo</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <button class="rounded-full bg-red-500 px-7 py-4 font-bold text-white shadow-xl shadow-red-950/40 transition hover:-translate-y-0.5 hover:bg-red-400">Add to cart</button>
                <button class="rounded-full border border-white/15 bg-white/5 px-7 py-4 font-bold text-white transition hover:bg-white/10">Ask support</button>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
        <h2 class="text-2xl font-black text-white">You may also need</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-3">
            @foreach ($relatedProducts as $relatedProduct)
                <a href="{{ route('products.show', $relatedProduct) }}" class="flex items-center gap-4 rounded-3xl border border-white/10 bg-white/[0.045] p-4 transition hover:-translate-y-1 hover:border-red-300/30">
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br {{ $relatedProduct->color }} text-2xl">{{ $relatedProduct->emoji }}</span>
                    <span>
                        <span class="block font-bold text-white">{{ $relatedProduct->name }}</span>
                        <span class="text-sm text-stone-400">{{ $relatedProduct->price() }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
</x-shop-layout>
