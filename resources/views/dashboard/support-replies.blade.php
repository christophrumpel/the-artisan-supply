<x-layouts::app :title="__('Support replies')">
    <div class="min-h-full bg-zinc-50/70 px-4 py-6 dark:bg-zinc-950/40 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-6xl flex-col gap-6">
            <div class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="relative isolate px-6 py-7 sm:px-8">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.14),transparent_34%),linear-gradient(135deg,rgba(239,246,255,0.95),rgba(255,255,255,0.9))] dark:bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.22),transparent_34%),linear-gradient(135deg,rgba(24,24,27,0.98),rgba(9,9,11,0.98))]"></div>
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <flux:badge color="blue">Feature 2</flux:badge>
                            <flux:heading class="mt-3" size="xl">Support replies</flux:heading>
                            <flux:text class="mt-2 text-base">A focused inbox for customer questions. Drafts appear inline, exactly where the support team expects them.</flux:text>
                        </div>

                        <div class="rounded-2xl border border-white/70 bg-white/75 px-4 py-3 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/70">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Inbox</p>
                            <p class="mt-1 text-2xl font-black text-zinc-950 dark:text-white">{{ $supportMessages->count() }}</p>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-5">
                @forelse ($supportMessages as $message)
                    <article class="overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="grid lg:grid-cols-[18rem_1fr]">
                            <aside class="border-b border-zinc-100 bg-zinc-50/80 p-5 dark:border-zinc-800 dark:bg-zinc-900/70 lg:border-b-0 lg:border-r">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-lg font-black text-white">
                                        {{ str($message->customer_name)->substr(0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-bold text-zinc-950 dark:text-white">{{ $message->customer_name }}</p>
                                        <p class="truncate text-xs text-zinc-500">{{ $message->customer_email }}</p>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap gap-2">
                                    <flux:badge :color="$message->draft_reply ? 'lime' : 'amber'">{{ $message->draft_reply ? 'Drafted' : 'Needs reply' }}</flux:badge>
                                    <flux:badge>Incoming email</flux:badge>
                                </div>
                            </aside>

                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-zinc-500">Subject</p>
                                        <h2 class="mt-1 text-xl font-black tracking-tight text-zinc-950 dark:text-white">{{ $message->subject }}</h2>
                                    </div>

                                    @if ($message->draft_reply === null)
                                        <form method="POST" action="{{ route('dashboard.support-replies.draft', $message) }}">
                                            @csrf
                                            <flux:button size="sm" variant="primary" type="submit">Draft reply</flux:button>
                                        </form>
                                    @endif
                                </div>

                                <div class="mt-5 rounded-2xl border border-zinc-200 bg-zinc-50 p-4 text-sm leading-6 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/70 dark:text-zinc-200">
                                    {{ $message->message }}
                                </div>

                                <div class="mt-4 rounded-2xl border border-blue-100 bg-blue-50/80 p-4 dark:border-blue-950 dark:bg-blue-950/30">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-700 dark:text-blue-200">Draft reply</p>
                                        @if ($message->draft_reply)
                                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-blue-700 shadow-sm dark:bg-blue-950 dark:text-blue-200">Ready to review</span>
                                        @endif
                                    </div>

                                    @if ($message->draft_reply)
                                        <p class="mt-3 text-sm leading-6 text-blue-950 dark:text-blue-100">{{ $message->draft_reply }}</p>
                                    @else
                                        <p class="mt-3 text-sm text-blue-700 dark:text-blue-200">No draft yet. Click once to add the current placeholder reply under this email.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[1.75rem] border border-dashed border-zinc-300 bg-white p-10 text-center text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl dark:bg-blue-950/30">💬</div>
                        <p class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">No incoming emails yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
