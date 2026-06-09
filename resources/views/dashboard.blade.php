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

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-zinc-950 dark:text-white">Nightwatch MCP</p>
                            <p class="mt-1 text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                                {{ $nightwatchConnected ? 'Connected. The dashboard assistant can use Nightwatch MCP tools.' : 'Connect once to let the dashboard assistant use Nightwatch MCP tools.' }}
                            </p>
                        </div>

                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $nightwatchConnected ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">
                            {{ $nightwatchConnected ? 'Connected' : 'Not connected' }}
                        </span>
                    </div>

                    @if (! $nightwatchConnected)
                        <flux:button class="mt-4" variant="primary" icon="link" :href="route('dashboard.nightwatch.connect')">
                            Connect Nightwatch
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
