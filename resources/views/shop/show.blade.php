<x-shop-layout title="{{ $product->name }} | The Artisan Supply">
    <section class="mx-auto grid max-w-7xl gap-10 px-6 py-12 lg:grid-cols-[.95fr_1.05fr] lg:px-8 lg:py-20">
        <div class="relative">
            <div class="absolute -inset-5 rounded-[3rem] bg-gradient-to-br {{ $product->color }} opacity-30 blur-3xl"></div>
            <div class="relative aspect-square overflow-hidden rounded-[2.5rem] border border-white/10 bg-gradient-to-br {{ $product->color }} shadow-2xl shadow-black/40">
                @if ($product->image_path)
                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                @else
                    <div class="grid h-full w-full place-items-center text-[12rem]">{{ $product->emoji }}</div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#140b0a]/45 via-transparent to-transparent"></div>
                <div class="absolute left-6 top-6 rounded-full bg-black/30 px-4 py-2 text-sm font-bold uppercase tracking-widest text-white backdrop-blur">{{ $product->badge ?? 'Artifact' }}</div>
            </div>
        </div>

        <div class="self-center">
            <a href="{{ route('home') }}" class="text-sm font-bold text-red-200 hover:text-red-100">← Back to products</a>
            <h1 class="mt-6 text-5xl font-black tracking-tight text-white sm:text-6xl">{{ $product->name }}</h1>
            <p class="mt-4 text-2xl font-serif italic text-red-100">{{ $product->tagline }}</p>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">{{ $product->description }}</p>

            <div class="mt-8 flex items-center gap-5">
                <p class="text-4xl font-black text-white">{{ $product->price() }}</p>
                <p class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-stone-300">{{ $product->inventory }} left</p>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <button class="rounded-full bg-red-500 px-7 py-4 font-bold text-white shadow-xl shadow-red-950/40 transition hover:-translate-y-0.5 hover:bg-red-400">Add to cart</button>
                <a href="{{ route('support') }}" class="rounded-full border border-white/15 bg-white/5 px-7 py-4 font-bold text-white transition hover:bg-white/10">Ask support</a>
            </div>
        </div>
    </section>
</x-shop-layout>
