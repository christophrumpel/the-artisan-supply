<x-shop-layout title="Support | The Artisan Supply">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8 lg:py-24">
        <div class="rounded-[2.5rem] border border-white/10 bg-white/[0.055] p-8 shadow-2xl shadow-black/30 backdrop-blur md:p-12">
            <p class="text-sm font-bold uppercase tracking-[0.3em] text-red-200">Support desk</p>
            <h1 class="mt-4 text-5xl font-black tracking-tight text-white">Questions from the supply closet.</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-stone-300">
                A tiny FAQ surface for the later AI demo: incoming support messages can search this knowledge base and draft a helpful reply.
            </p>

            <div class="mt-10 grid gap-4">
                @foreach ($questions as $item)
                    <article class="rounded-3xl border border-white/10 bg-[#1d100f]/80 p-6">
                        <h2 class="text-lg font-black text-white">{{ $item['question'] }}</h2>
                        <p class="mt-3 leading-7 text-stone-300">{{ $item['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="mt-8 rounded-[2rem] border border-dashed border-red-300/25 bg-red-500/10 p-6">
            <p class="text-sm font-bold uppercase tracking-widest text-red-100">Coming in the AI chapter</p>
            <p class="mt-2 text-stone-300">Paste a customer email here, search the shop FAQ, and prepare a reply draft without turning this demo into a full helpdesk app.</p>
        </div>
    </section>
</x-shop-layout>
