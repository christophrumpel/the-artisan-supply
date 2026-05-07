<x-shop-layout title="Support | The Artisan Supply">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8 lg:py-24">
        <div class="rounded-[2.5rem] border border-white/10 bg-white/[0.055] p-8 shadow-2xl shadow-black/30 backdrop-blur md:p-12">
            <p class="text-sm font-bold uppercase tracking-[0.3em] text-red-200">Support desk</p>
            <h1 class="mt-4 text-5xl font-black tracking-tight text-white">Questions from the supply closet.</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-stone-300">
                Send us a quick voice message. The dashboard shows the raw audio first, and the AI chapter will add transcription and reply drafting later.
            </p>

            <div class="mt-10 grid gap-4">
                @foreach ($questions as $item)
                    <article class="rounded-3xl border border-white/10 bg-[#1d100f]/80 p-6">
                        <h2 class="text-lg font-black text-white">{{ $item['question'] }}</h2>
                        <p class="mt-3 leading-7 text-stone-300">{{ $item['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="mt-8 rounded-[2rem] border border-red-300/20 bg-red-500/10 p-6 backdrop-blur">
            <p class="text-sm font-bold uppercase tracking-widest text-red-100">Voice support</p>
            <h2 class="mt-2 text-2xl font-black text-white">Record a message for the support team.</h2>
            <p class="mt-2 max-w-2xl text-stone-300">This is intentionally simple for the before-state: collect contact details, record audio, and save it for the admin inbox.</p>

            <form id="voice-support-form" class="mt-6 grid gap-4" data-endpoint="{{ route('support.voice-messages.store') }}">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-bold text-red-100">Name</span>
                        <input name="customer_name" type="text" required class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder:text-stone-500 focus:border-red-300 focus:outline-none" placeholder="Taylor from Production">
                    </label>

                    <label class="block">
                        <span class="text-sm font-bold text-red-100">Email</span>
                        <input name="customer_email" type="email" required class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder:text-stone-500 focus:border-red-300 focus:outline-none" placeholder="you@example.com">
                    </label>
                </div>

                <div class="rounded-3xl border border-white/10 bg-[#1d100f]/80 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p id="recorder-status" class="font-bold text-white">Ready to record</p>
                            <p class="mt-1 text-sm text-stone-400">Record up to a short browser voice note, then submit it with the form.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button id="record-button" type="button" class="rounded-full bg-red-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-red-950/30 transition hover:bg-red-400">Start recording</button>
                            <button id="stop-button" type="button" disabled class="rounded-full border border-white/15 px-5 py-3 text-sm font-black text-white/80 transition enabled:hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40">Stop</button>
                        </div>
                    </div>

                    <audio id="audio-preview" controls class="mt-5 hidden w-full"></audio>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p id="voice-support-feedback" class="text-sm font-semibold text-stone-300"></p>
                    <button id="submit-button" type="submit" disabled class="rounded-full bg-white px-6 py-3 text-sm font-black text-[#140b0a] shadow-sm transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-50">Send voice message</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const form = document.getElementById('voice-support-form');
            const recordButton = document.getElementById('record-button');
            const stopButton = document.getElementById('stop-button');
            const submitButton = document.getElementById('submit-button');
            const recorderStatus = document.getElementById('recorder-status');
            const feedback = document.getElementById('voice-support-feedback');
            const audioPreview = document.getElementById('audio-preview');

            if (! form || ! recordButton || ! stopButton || ! submitButton || ! recorderStatus || ! feedback || ! audioPreview) {
                return;
            }

            let recorder;
            let stream;
            let chunks = [];
            let audioBlob;

            const setFeedback = (message) => {
                feedback.textContent = message;
            };

            const resetRecording = () => {
                chunks = [];
                audioBlob = null;
                submitButton.disabled = true;
                audioPreview.removeAttribute('src');
                audioPreview.classList.add('hidden');
            };

            const stopStream = () => {
                stream?.getTracks().forEach((track) => track.stop());
                stream = null;
            };

            if (! window.isSecureContext) {
                recorderStatus.textContent = 'Audio recording needs HTTPS or localhost.';
                recordButton.disabled = true;
                return;
            }

            if (! navigator.mediaDevices?.getUserMedia || ! window.MediaRecorder) {
                recorderStatus.textContent = 'Audio recording is not supported in this browser.';
                recordButton.disabled = true;
                return;
            }

            recordButton.addEventListener('click', async () => {
                setFeedback('');
                resetRecording();
                recorderStatus.textContent = 'Asking for microphone access...';
                recordButton.disabled = true;

                try {
                    stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    const options = MediaRecorder.isTypeSupported('audio/webm;codecs=opus')
                        ? { mimeType: 'audio/webm;codecs=opus' }
                        : undefined;

                    recorder = new MediaRecorder(stream, options);

                    recorder.addEventListener('dataavailable', (event) => {
                        if (event.data.size > 0) {
                            chunks.push(event.data);
                        }
                    });

                    recorder.addEventListener('stop', () => {
                        audioBlob = new Blob(chunks, { type: recorder.mimeType || 'audio/webm' });
                        audioPreview.src = URL.createObjectURL(audioBlob);
                        audioPreview.classList.remove('hidden');
                        submitButton.disabled = false;
                        stopButton.disabled = true;
                        recordButton.disabled = false;
                        recorderStatus.textContent = 'Recording ready';
                        stopStream();
                    });

                    recorder.start();
                    recorderStatus.textContent = 'Recording...';
                    stopButton.disabled = false;
                } catch (error) {
                    recorderStatus.textContent = 'Could not access the microphone.';
                    setFeedback(error?.message || 'Please allow microphone access and try again.');
                    recordButton.disabled = false;
                    stopButton.disabled = true;
                    stopStream();
                }
            });

            stopButton.addEventListener('click', () => {
                if (recorder && recorder.state !== 'inactive') {
                    recorder.stop();
                }
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                if (! audioBlob) {
                    setFeedback('Please record a message first.');
                    return;
                }

                const data = new FormData(form);
                data.append('audio', audioBlob, 'support-message.webm');

                submitButton.disabled = true;
                setFeedback('Sending voice message...');

                try {
                    const response = await fetch(form.dataset.endpoint, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: data,
                    });

                    if (response.ok) {
                        const json = await response.json();
                        form.reset();
                        resetRecording();
                        recorderStatus.textContent = 'Ready to record';
                        setFeedback(json.message);
                        return;
                    }

                    const json = await response.json().catch(() => null);
                    setFeedback(json?.message || 'Something went wrong. Please try again.');
                    submitButton.disabled = false;
                } catch (error) {
                    setFeedback('Could not send the message. Please try again.');
                    submitButton.disabled = false;
                }
            });
        })();
    </script>
</x-shop-layout>
