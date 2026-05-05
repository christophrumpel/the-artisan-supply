<x-layouts::app :title="__('Support replies')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:badge color="blue">Feature 2</flux:badge>
                <flux:heading class="mt-3" size="xl">Support replies</flux:heading>
                <flux:text class="mt-2 max-w-3xl">Review incoming customer emails and add one draft reply directly underneath the message. Later, the AI SDK will generate this draft from the shop data.</flux:text>
            </div>

            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="space-y-4">
            @forelse ($supportMessages as $message)
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm text-zinc-500">{{ $message->customer_name }} · {{ $message->customer_email }}</p>
                            <p class="mt-2 font-semibold text-zinc-900 dark:text-zinc-100">{{ $message->subject }}</p>
                        </div>
                        <flux:badge :color="$message->draft_reply ? 'lime' : 'amber'">{{ $message->draft_reply ? 'Drafted' : 'Needs reply' }}</flux:badge>
                    </div>

                    <div class="mt-4 rounded-lg border border-zinc-200 bg-zinc-50 p-4 text-sm leading-6 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/70 dark:text-zinc-200">
                        {{ $message->message }}
                    </div>

                    <div class="mt-4 rounded-lg border border-blue-100 bg-blue-50 p-4 dark:border-blue-950 dark:bg-blue-950/30">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="font-medium text-blue-950 dark:text-blue-100">Draft reply</p>
                            @if ($message->draft_reply === null)
                                <form method="POST" action="{{ route('dashboard.support-replies.draft', $message) }}">
                                    @csrf
                                    <flux:button size="sm" variant="primary" type="submit">Draft reply</flux:button>
                                </form>
                            @endif
                        </div>

                        @if ($message->draft_reply)
                            <p class="mt-3 text-sm leading-6 text-blue-950 dark:text-blue-100">{{ $message->draft_reply }}</p>
                        @else
                            <p class="mt-3 text-sm text-blue-700 dark:text-blue-200">No draft yet. Click once to add the current placeholder reply under this email.</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-zinc-300 p-8 text-center text-zinc-500 dark:border-zinc-700">No incoming emails yet.</div>
            @endforelse
        </div>
    </div>
</x-layouts::app>
