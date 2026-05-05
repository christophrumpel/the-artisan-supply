<x-layouts::app :title="__('Dashboard')">
    <div class="min-h-full bg-zinc-50/70 px-4 py-6 dark:bg-zinc-950/40 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-6">
            <div class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="relative isolate px-6 py-8 sm:px-8">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_12%_20%,rgba(255,45,32,0.15),transparent_32%),radial-gradient(circle_at_88%_10%,rgba(37,99,235,0.12),transparent_28%),linear-gradient(135deg,rgba(255,248,241,0.95),rgba(255,255,255,0.92))] dark:bg-[radial-gradient(circle_at_12%_20%,rgba(255,45,32,0.2),transparent_32%),radial-gradient(circle_at_88%_10%,rgba(37,99,235,0.18),transparent_28%),linear-gradient(135deg,rgba(24,24,27,0.98),rgba(9,9,11,0.98))]"></div>
                    <div class="max-w-3xl">
                        <p class="text-sm font-black uppercase tracking-[0.22em] text-red-500">Demo control room</p>
                        <flux:heading class="mt-3" size="xl">The Artisan Supply dashboard</flux:heading>
                        <flux:text class="mt-3 text-base">
                            Three practical shop workflows, kept simple enough for the video: manage assets, draft support replies, and review the knowledge base before AI takes over the repetitive parts.
                        </flux:text>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">
                <a href="{{ route('dashboard.assets.index') }}" wire:navigate class="group overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="h-2 bg-gradient-to-r from-red-500 to-orange-300"></div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <flux:badge color="red">Feature 1</flux:badge>
                                <flux:heading class="mt-3" size="lg">Assets Manager</flux:heading>
                            </div>
                            <div class="rounded-2xl bg-red-50 p-3 text-2xl transition group-hover:scale-110 dark:bg-red-950/40">📎</div>
                        </div>
                        <flux:text class="mt-4">Upload assets, review file details, and manually maintain title, description, and image alt text.</flux:text>
                        <div class="mt-6 flex items-end justify-between">
                            <p class="text-3xl font-black text-zinc-950 dark:text-white">{{ $assetCount }}</p>
                            <p class="text-sm font-bold text-red-600 dark:text-red-400">Open manager →</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('dashboard.support-replies.index') }}" wire:navigate class="group overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="h-2 bg-gradient-to-r from-blue-600 to-sky-300"></div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <flux:badge color="blue">Feature 2</flux:badge>
                                <flux:heading class="mt-3" size="lg">Support replies</flux:heading>
                            </div>
                            <div class="rounded-2xl bg-blue-50 p-3 text-2xl transition group-hover:scale-110 dark:bg-blue-950/40">💬</div>
                        </div>
                        <flux:text class="mt-4">Review incoming customer emails and draft one reply directly under each message.</flux:text>
                        <div class="mt-6 flex items-end justify-between">
                            <p class="text-3xl font-black text-zinc-950 dark:text-white">{{ $draftedReplyCount }} / {{ $supportMessageCount }}</p>
                            <p class="text-sm font-bold text-blue-600 dark:text-blue-400">Open inbox →</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('dashboard.knowledge-base.index') }}" wire:navigate class="group overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="h-2 bg-gradient-to-r from-lime-500 to-emerald-300"></div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <flux:badge color="lime">Data source</flux:badge>
                                <flux:heading class="mt-3" size="lg">Knowledge base</flux:heading>
                            </div>
                            <div class="rounded-2xl bg-lime-50 p-3 text-2xl transition group-hover:scale-110 dark:bg-lime-950/40">📚</div>
                        </div>
                        <flux:text class="mt-4">Review the FAQ entries used by the support reply drafter.</flux:text>
                        <div class="mt-6 flex items-end justify-between">
                            <p class="text-3xl font-black text-zinc-950 dark:text-white">{{ $faqCount }}</p>
                            <p class="text-sm font-bold text-lime-700 dark:text-lime-400">Open data →</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>
