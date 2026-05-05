<x-layouts::app :title="__('Knowledge base')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:badge color="lime">Data source</flux:badge>
                <flux:heading class="mt-3" size="xl">FAQ knowledge base</flux:heading>
                <flux:text class="mt-2 max-w-3xl">This is the source data the support reply can search once AI is added.</flux:text>
            </div>
            <flux:badge>{{ $faqs->count() }} entries</flux:badge>
        </div>

        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($faqs as $faq)
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $faq->question }}</p>
                    <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">{{ $faq->answer }}</p>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-zinc-300 p-8 text-center text-zinc-500 dark:border-zinc-700 md:col-span-2 xl:col-span-3">No FAQ entries yet.</div>
            @endforelse
        </div>
    </div>
</x-layouts::app>
