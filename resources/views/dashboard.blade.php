<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:heading size="xl">The Artisan Supply dashboard</flux:heading>
                <flux:text class="mt-2 max-w-3xl">
                    A simple overview of the shop workflows: manage product assets, draft support replies, and review the knowledge base before the AI integrations are wired in.
                </flux:text>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <a href="{{ route('dashboard.assets.index') }}" wire:navigate class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-red-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-red-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <flux:badge color="red">Feature 1</flux:badge>
                        <flux:heading class="mt-3" size="lg">Assets Manager</flux:heading>
                        <flux:text class="mt-2">Upload assets, review file details, and manually maintain title, description, and image alt text.</flux:text>
                    </div>
                    <div class="rounded-xl bg-red-100 p-3 text-2xl dark:bg-red-950/50">📎</div>
                </div>
                <p class="mt-5 text-sm font-medium text-red-600 dark:text-red-400">{{ $assetCount }} uploaded assets →</p>
            </a>

            <a href="{{ route('dashboard.support-replies.index') }}" wire:navigate class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <flux:badge color="blue">Feature 2</flux:badge>
                        <flux:heading class="mt-3" size="lg">Support replies</flux:heading>
                        <flux:text class="mt-2">Review incoming customer emails and draft one reply directly under each message.</flux:text>
                    </div>
                    <div class="rounded-xl bg-blue-100 p-3 text-2xl dark:bg-blue-950/50">💬</div>
                </div>
                <p class="mt-5 text-sm font-medium text-blue-600 dark:text-blue-400">{{ $draftedReplyCount }} / {{ $supportMessageCount }} replies drafted →</p>
            </a>

            <a href="{{ route('dashboard.knowledge-base.index') }}" wire:navigate class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-lime-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-lime-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <flux:badge color="lime">Data source</flux:badge>
                        <flux:heading class="mt-3" size="lg">Knowledge base</flux:heading>
                        <flux:text class="mt-2">Review the FAQ entries used by the support reply drafter.</flux:text>
                    </div>
                    <div class="rounded-xl bg-lime-100 p-3 text-2xl dark:bg-lime-950/50">📚</div>
                </div>
                <p class="mt-5 text-sm font-medium text-lime-700 dark:text-lime-400">{{ $faqCount }} FAQ entries →</p>
            </a>
        </div>
    </div>
</x-layouts::app>
