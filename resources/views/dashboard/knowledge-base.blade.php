<x-layouts::app :title="__('Knowledge base')">
    <div class="min-h-full bg-zinc-50/70 px-4 py-6 dark:bg-zinc-950/40 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-6xl flex-col gap-6">
            <div class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="relative isolate px-6 py-7 sm:px-8">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,rgba(132,204,22,0.16),transparent_34%),linear-gradient(135deg,rgba(247,254,231,0.95),rgba(255,255,255,0.9))] dark:bg-[radial-gradient(circle_at_top_left,rgba(132,204,22,0.18),transparent_34%),linear-gradient(135deg,rgba(24,24,27,0.98),rgba(9,9,11,0.98))]"></div>
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <flux:badge color="lime">Data source</flux:badge>
                            <flux:heading class="mt-3" size="xl">Knowledge base</flux:heading>
                            <flux:text class="mt-2 text-base">Add support knowledge manually for now. Later, this is the perfect place to turn a PDF into structured entries with title and text.</flux:text>
                        </div>
                        <div class="rounded-2xl border border-white/70 bg-white/75 px-4 py-3 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/70">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Entries</p>
                            <p class="mt-1 text-2xl font-black text-zinc-950 dark:text-white">{{ $faqs->count() }}</p>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[22rem_1fr]">
                <form class="h-fit overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.knowledge-base.store') }}">
                    @csrf
                    <div class="border-b border-zinc-100 bg-zinc-50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-900/80">
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-lime-600 dark:text-lime-400">New entry</p>
                        <p class="mt-1 text-sm text-zinc-500">Add a clear title and the text the support workflow can use.</p>
                    </div>

                    <div class="space-y-4 p-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="title">Title</label>
                            <input id="title" name="title" class="mt-2 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Return policy for time machines">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="text">Text</label>
                            <textarea id="text" name="text" rows="7" class="mt-2 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Customers can return unopened items within 14 days..."></textarea>
                        </div>

                        <flux:button class="w-full" variant="primary" type="submit">Add entry</flux:button>
                    </div>
                </form>

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
                        <div class="rounded-[1.75rem] border border-dashed border-zinc-300 bg-white p-10 text-center text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 md:col-span-2">No knowledge base entries yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
