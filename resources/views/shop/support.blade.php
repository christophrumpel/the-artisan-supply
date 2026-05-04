<x-shop-layout title="Support | The Artisan Supply">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8 lg:py-24">
        <div class="rounded-[2.5rem] border border-white/10 bg-white/[0.055] p-8 shadow-2xl shadow-black/30 backdrop-blur md:p-12">
            <p class="text-sm font-bold uppercase tracking-[0.3em] text-red-200">Support desk</p>
            <h1 class="mt-4 text-5xl font-black tracking-tight text-white">Questions from the supply closet.</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-stone-300">
                Send us a quick voice message. The dashboard shows the raw audio first, and the AI chapter will add transcription and reply drafting later.
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

        <div class="mt-8 rounded-[2rem] border border-red-300/20 bg-red-500/10 p-6 backdrop-blur">
            <h2 class="text-2xl font-black text-white">Record a message for the support team.</h2>

            <form id="voice-support-form" class="mt-6 grid gap-4" data-endpoint="{{ route('support.voice-messages.store') }}">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-bold text-red-100">Name</span>
                        <input name="customer_name" type="text" required data-voice-name class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder:text-stone-500 focus:border-red-300 focus:outline-none" placeholder="Taylor from Production">
                    </label>

                    <label class="block">
                        <span class="text-sm font-bold text-red-100">Email</span>
                        <input name="customer_email" type="email" required data-voice-email class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder:text-stone-500 focus:border-red-300 focus:outline-none" placeholder="you@example.com">
                    </label>
                </div>

                <div data-voice-panel class="rounded-3xl border border-white/10 bg-white/10 p-4 transition" aria-live="polite">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-start gap-3">
                            <div data-voice-indicator class="mt-1 h-3 w-3 shrink-0 rounded-full bg-stone-500 ring-4 ring-white/10"></div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p data-voice-status-title class="font-black text-white">Ready when you are</p>
                                    <span data-voice-timer class="hidden rounded-full bg-white/10 px-2 py-0.5 text-xs font-bold tabular-nums text-red-100">00:00</span>
                                </div>
                                <p data-voice-status-detail class="mt-1 text-sm font-medium text-stone-300">Enter name and email to enable recording.</p>
                            </div>
                        </div>

                        <button data-voice-button type="button" disabled class="rounded-full bg-red-500 px-6 py-3 text-sm font-black text-white shadow-lg shadow-red-950/30 transition hover:bg-red-400 disabled:cursor-not-allowed disabled:opacity-50">
                            Start recording
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-shop-layout>
