<x-layouts::app :title="__('Support replies')">
    <div class="min-h-full bg-zinc-50/70 px-4 py-6 dark:bg-zinc-950/40 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <div class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="relative isolate px-6 py-7 sm:px-8">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.14),transparent_34%),linear-gradient(135deg,rgba(239,246,255,0.95),rgba(255,255,255,0.9))] dark:bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.22),transparent_34%),linear-gradient(135deg,rgba(24,24,27,0.98),rgba(9,9,11,0.98))]"></div>
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <flux:badge color="blue">Feature 2</flux:badge>
                            <flux:heading class="mt-3" size="xl">Support replies</flux:heading>
                            <flux:text class="mt-2 text-base">Read customer emails and add a draft directly beneath each message. The current form is manual; the AI SDK will fill this later.</flux:text>
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

            <div class="grid gap-4">
                @forelse ($supportMessages as $message)
                    <article class="rounded-[1.75rem] border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 sm:p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-lg font-black text-white">
                                {{ str($message->customer_name)->substr(0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-zinc-500">{{ $message->customer_name }} · {{ $message->customer_email }}</p>
                                        <h2 class="mt-1 text-xl font-black tracking-tight text-zinc-950 dark:text-white">{{ $message->subject }}</h2>
                                    </div>
                                    <flux:badge :color="$message->draft_reply ? 'lime' : 'amber'">{{ $message->draft_reply ? 'Drafted' : 'Needs reply' }}</flux:badge>
                                </div>

                                <div class="mt-5 rounded-2xl border border-zinc-200 bg-zinc-50 p-4 text-sm leading-6 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/70 dark:text-zinc-200">
                                    {{ $message->message }}
                                </div>

                                @if ($message->draft_reply)
                                    <div class="mt-4 border-l-4 border-blue-500 pl-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-300">Draft reply</p>
                                        <p class="mt-2 rounded-2xl bg-blue-50 p-4 text-sm leading-6 text-blue-950 dark:bg-blue-950/30 dark:text-blue-100">{{ $message->draft_reply }}</p>
                                    </div>
                                @else
                                    <details class="group mt-4">
                                        <summary class="inline-flex cursor-pointer list-none items-center rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-700 shadow-sm transition hover:border-blue-200 hover:text-blue-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:border-blue-800 dark:hover:text-blue-300">
                                            Draft reply
                                        </summary>
                                        <form class="mt-3 rounded-2xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900" method="POST" action="{{ route('dashboard.support-replies.draft', $message) }}">
                                            @csrf
                                            <label class="block text-xs font-bold uppercase tracking-[0.16em] text-zinc-500" for="draft_reply_{{ $message->id }}">Reply text</label>
                                            <textarea id="draft_reply_{{ $message->id }}" name="draft_reply" rows="4" class="mt-2 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900" placeholder="Hi {{ $message->customer_name }}, thanks for reaching out..."></textarea>
                                            <div class="mt-3 flex justify-end">
                                                <flux:button size="sm" variant="primary" type="submit">Save draft</flux:button>
                                            </div>
                                        </form>
                                    </details>
                                @endif
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
