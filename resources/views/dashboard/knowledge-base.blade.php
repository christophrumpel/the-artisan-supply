<x-layouts::app :title="__('Knowledge base')">
    <div class="min-h-full bg-zinc-50/70 px-4 py-6 dark:bg-zinc-950/40 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-6xl flex-col gap-6">
            <div class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="relative isolate px-6 py-7 sm:px-8">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,rgba(132,204,22,0.16),transparent_34%),linear-gradient(135deg,rgba(247,254,231,0.95),rgba(255,255,255,0.9))] dark:bg-[radial-gradient(circle_at_top_left,rgba(132,204,22,0.18),transparent_34%),linear-gradient(135deg,rgba(24,24,27,0.98),rgba(9,9,11,0.98))]"></div>
                    <div class="flex items-end justify-between gap-4">
                        <div class="max-w-3xl">
                            <flux:badge color="lime">Data source</flux:badge>
                            <flux:heading class="mt-3" size="xl">FAQ knowledge base</flux:heading>
                            <flux:text class="mt-2 text-base">Small, readable source material for the support reply workflow. Later this becomes the retrieval context.</flux:text>
                        </div>
                        <div class="rounded-2xl border border-white/70 bg-white/75 px-4 py-3 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/70">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Entries</p>
                            <p class="mt-1 text-2xl font-black text-zinc-950 dark:text-white">{{ $faqs->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @forelse ($faqs as $faq)
                    <div class="rounded-[1.5rem] border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="flex gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-lime-50 text-lg dark:bg-lime-950/40">📚</div>
                            <div>
                                <p class="font-black leading-snug text-zinc-950 dark:text-white">{{ $faq->question }}</p>
                                <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[1.75rem] border border-dashed border-zinc-300 bg-white p-10 text-center text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 md:col-span-2">No FAQ entries yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
