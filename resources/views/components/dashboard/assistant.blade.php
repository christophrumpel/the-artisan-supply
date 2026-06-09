<div
    class="fixed bottom-5 right-5 z-50"
    data-dashboard-assistant
    data-endpoint="{{ route('dashboard.assistant') }}"
>
    <div
        class="mb-3 hidden w-[min(calc(100vw-2.5rem),24rem)] overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-2xl shadow-zinc-950/20 dark:border-zinc-700 dark:bg-zinc-900"
        data-assistant-panel
    >
        <div class="border-b border-zinc-100 bg-zinc-50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900/80">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-zinc-950 dark:text-white">Dashboard assistant</p>
                    <p class="mt-0.5 text-xs text-zinc-500">Ask about current shop data.</p>
                </div>

                <button
                    type="button"
                    class="rounded-lg p-2 text-zinc-500 transition hover:bg-zinc-200/70 hover:text-zinc-950 dark:hover:bg-zinc-800 dark:hover:text-white"
                    data-assistant-close
                    aria-label="Close assistant"
                >
                    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.28 4.28a.75.75 0 0 1 1.06 0L10 8.94l4.66-4.66a.75.75 0 1 1 1.06 1.06L11.06 10l4.66 4.66a.75.75 0 1 1-1.06 1.06L10 11.06l-4.66 4.66a.75.75 0 0 1-1.06-1.06L8.94 10 4.28 5.34a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="max-h-80 space-y-3 overflow-y-auto p-4" data-assistant-messages>
            <div class="rounded-2xl bg-zinc-100 px-3 py-2 text-sm leading-6 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                Ask me how many products, support messages, FAQs, or assets are in the database.
            </div>
        </div>

        <form class="border-t border-zinc-100 p-3 dark:border-zinc-800" data-assistant-form>
            <div class="flex gap-2">
                <input
                    name="message"
                    class="min-w-0 flex-1 rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 outline-none transition placeholder:text-zinc-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:focus:ring-red-950"
                    placeholder="How many support questions?"
                    autocomplete="off"
                    data-assistant-input
                >

                <button
                    type="submit"
                    class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-500 text-white shadow-sm transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-60"
                    data-assistant-submit
                    aria-label="Send message"
                >
                    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M3.105 2.289a.75.75 0 0 1 .814-.09l13.5 7a.75.75 0 0 1 0 1.302l-13.5 7A.75.75 0 0 1 2.875 16.6l1.635-5.72L10.75 10 4.51 9.12 2.875 3.4a.75.75 0 0 1 .23-1.111Z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <button
        type="button"
        class="ml-auto flex size-14 items-center justify-center rounded-full bg-red-500 text-white shadow-xl shadow-red-950/25 transition hover:-translate-y-0.5 hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-200 dark:focus:ring-red-950"
        data-assistant-toggle
        aria-label="Open dashboard assistant"
    >
        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75h6.75m-6.75 3h3.75M21 12c0 4.142-4.03 7.5-9 7.5a10.6 10.6 0 0 1-3.71-.655L3 20.25l1.568-4.182A6.77 6.77 0 0 1 3 12c0-4.142 4.03-7.5 9-7.5s9 3.358 9 7.5Z" />
        </svg>
    </button>
</div>

<script>
    (() => {
        const root = document.querySelector('[data-dashboard-assistant]');

        if (! root || root.dataset.initialized) {
            return;
        }

        root.dataset.initialized = 'true';

        const endpoint = root.dataset.endpoint;
        const panel = root.querySelector('[data-assistant-panel]');
        const toggle = root.querySelector('[data-assistant-toggle]');
        const close = root.querySelector('[data-assistant-close]');
        const form = root.querySelector('[data-assistant-form]');
        const input = root.querySelector('[data-assistant-input]');
        const submit = root.querySelector('[data-assistant-submit]');
        const messages = root.querySelector('[data-assistant-messages]');
        const token = document.querySelector('meta[name="csrf-token"]')?.content;

        const appendMessage = (text, fromUser = false) => {
            const bubble = document.createElement('div');

            bubble.className = fromUser
                ? 'ml-auto max-w-[85%] rounded-2xl bg-red-500 px-3 py-2 text-sm leading-6 text-white'
                : 'max-w-[85%] rounded-2xl bg-zinc-100 px-3 py-2 text-sm leading-6 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200';

            bubble.textContent = text;
            messages.appendChild(bubble);
            messages.scrollTop = messages.scrollHeight;

            return bubble;
        };

        const setLoading = (loading) => {
            submit.disabled = loading;
            input.disabled = loading;
        };

        toggle.addEventListener('click', () => {
            panel.classList.toggle('hidden');

            if (! panel.classList.contains('hidden')) {
                input.focus();
            }
        });

        close.addEventListener('click', () => {
            panel.classList.add('hidden');
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const message = input.value.trim();

            if (! message) {
                return;
            }

            appendMessage(message, true);
            input.value = '';
            setLoading(true);

            const pending = appendMessage('Checking the dashboard data...');

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await response.json();

                pending.textContent = data.message || 'No answer returned.';
            } catch (error) {
                pending.textContent = 'The assistant could not answer right now.';
            } finally {
                setLoading(false);
                input.focus();
            }
        });
    })();
</script>
