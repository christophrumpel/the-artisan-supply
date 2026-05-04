<x-shop-layout title="Studio | The Artisan Supply">
    <section class="mx-auto max-w-7xl px-6 py-12 lg:px-8 lg:py-20">
        <div class="max-w-3xl">
            <p class="text-sm font-bold uppercase tracking-[0.32em] text-red-200">Back office</p>
            <h1 class="mt-5 text-5xl font-black tracking-tight text-white">Three tiny workflows before AI helps.</h1>
            <p class="mt-6 text-lg leading-8 text-stone-300">These screens are intentionally simple. They give the video concrete places where we can add the Laravel AI SDK one feature at a time.</p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            <article class="rounded-[2rem] border border-white/10 bg-white/[0.05] p-6 backdrop-blur">
                <p class="text-sm font-bold uppercase tracking-widest text-red-100">1. Asset metadata</p>
                <h2 class="mt-3 text-2xl font-black text-white">Uploaded assets</h2>
                <p class="mt-3 text-sm leading-6 text-stone-400">Today, title and description are empty. Later, AI can inspect the upload and fill them automatically.</p>
                <div class="mt-6 space-y-3">
                    @foreach ($assets as $asset)
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="font-bold text-white">{{ $asset->filename }}</p>
                            <p class="mt-1 text-sm text-stone-400">{{ $asset->product->name }} · {{ $asset->mime_type }} · {{ number_format($asset->size / 1000000, 1) }} MB</p>
                            <p class="mt-3 text-sm text-red-100">Title: {{ $asset->title ?? 'Not written yet' }}</p>
                            <p class="text-sm text-red-100">Description: {{ $asset->description ?? 'Not written yet' }}</p>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-[2rem] border border-white/10 bg-white/[0.05] p-6 backdrop-blur">
                <p class="text-sm font-bold uppercase tracking-widest text-red-100">2. Product image</p>
                <h2 class="mt-3 text-2xl font-black text-white">Image requests</h2>
                <p class="mt-3 text-sm leading-6 text-stone-400">Today, this is just a prompt waiting for a designer. Later, AI can generate the product image directly.</p>
                <div class="mt-6 space-y-3">
                    @foreach ($imageRequests as $request)
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="font-bold text-white">{{ $request->product->name }}</p>
                            <p class="mt-2 text-sm leading-6 text-stone-400">“{{ $request->prompt }}”</p>
                            <p class="mt-3 inline-flex rounded-full bg-amber-400/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-100">{{ $request->status }}</p>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-[2rem] border border-white/10 bg-white/[0.05] p-6 backdrop-blur">
                <p class="text-sm font-bold uppercase tracking-widest text-red-100">3. Support reply</p>
                <h2 class="mt-3 text-2xl font-black text-white">Inbox</h2>
                <p class="mt-3 text-sm leading-6 text-stone-400">Today, support reads FAQs manually. Later, AI can search answers and draft a reply.</p>
                @foreach ($supportMessages as $message)
                    <div class="mt-6 rounded-2xl bg-black/20 p-4">
                        <p class="text-sm text-stone-400">{{ $message->customer_name }} · {{ $message->customer_email }}</p>
                        <p class="mt-2 font-bold text-white">{{ $message->subject }}</p>
                        <p class="mt-3 text-sm leading-6 text-stone-300">{{ $message->message }}</p>
                        <p class="mt-4 text-sm text-red-100">Draft reply: {{ $message->draft_reply ?? 'Nothing drafted yet' }}</p>
                    </div>
                @endforeach
            </article>
        </div>

        <div class="mt-8 rounded-[2rem] border border-white/10 bg-black/20 p-6">
            <p class="text-sm font-bold uppercase tracking-widest text-stone-400">FAQ source data</p>
            <div class="mt-4 grid gap-3 md:grid-cols-3">
                @foreach ($faqs as $faq)
                    <div class="rounded-2xl bg-white/[0.04] p-4">
                        <p class="font-bold text-white">{{ $faq->question }}</p>
                        <p class="mt-2 text-sm leading-6 text-stone-400">{{ $faq->answer }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-shop-layout>
