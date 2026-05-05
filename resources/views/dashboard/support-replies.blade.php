<x-layouts::app :title="__('Support replies')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:badge color="blue">Feature 3</flux:badge>
                <flux:heading class="mt-3" size="xl">Support replies</flux:heading>
                <flux:text class="mt-2 max-w-3xl">Paste a customer question and draft an answer from local FAQs/products.</flux:text>
            </div>

            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="grid gap-6 xl:grid-cols-[24rem_1fr]">
            <form class="space-y-3 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.support-replies.store') }}">
                @csrf
                <input name="customer_name" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Customer name" value="Nuno from Localhost">
                <input name="customer_email" type="email" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="customer@example.com" value="nuno@example.com">
                <input name="subject" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Subject">
                <textarea name="message" rows="4" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Customer question"></textarea>
                <flux:button class="w-full" variant="primary" type="submit">Draft reply from shop data</flux:button>
            </form>

            <div class="space-y-4">
                @forelse ($supportMessages as $message)
                    <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <p class="text-sm text-zinc-500">{{ $message->customer_name }} · {{ $message->customer_email }}</p>
                        <p class="mt-2 font-semibold text-zinc-900 dark:text-zinc-100">{{ $message->subject }}</p>
                        <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">{{ $message->message }}</p>
                        <div class="mt-4 rounded-lg bg-zinc-50 p-3 text-sm dark:bg-zinc-800/70">
                            <p class="font-medium text-zinc-500">Draft reply</p>
                            <p class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $message->draft_reply ?? 'Nothing drafted yet' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-zinc-300 p-8 text-center text-zinc-500 dark:border-zinc-700">No drafted replies yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
