<x-shop-layout title="The Artisan Supply">
    <section class="mx-auto max-w-7xl px-6 pb-14 pt-12 lg:px-8 lg:pt-20">
        <div class="max-w-3xl">
            <p class="text-sm font-bold uppercase tracking-[0.32em] text-red-200">The Artisan Supply</p>
            <h1 class="mt-5 text-5xl font-black tracking-tight text-white sm:text-7xl">
                Useful things for unserious Laravel developers.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">
                A tiny shop selling imaginary developer supplies. Beautifully unnecessary, suspiciously specific, and definitely not available in the official Laravel store.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product) }}" class="group overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/[0.045] shadow-xl shadow-black/20 backdrop-blur transition hover:-translate-y-1 hover:border-red-300/30 hover:bg-white/[0.07]">
                    <div class="relative aspect-square overflow-hidden bg-gradient-to-br {{ $product->color }}">
                        @if ($product->image_path)
                            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#140b0a]/60 via-transparent to-transparent"></div>
                        @else
                            <div class="grid h-full w-full place-items-center text-8xl">{{ $product->emoji }}</div>
                        @endif
                        @if ($product->badge)
                            <span class="absolute left-4 top-4 rounded-full bg-black/35 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white backdrop-blur">{{ $product->badge }}</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <h2 class="text-lg font-black text-white">{{ $product->name }}</h2>
                            <span class="font-bold text-red-100">{{ $product->price() }}</span>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-stone-400">{{ $product->tagline }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</x-shop-layout>
