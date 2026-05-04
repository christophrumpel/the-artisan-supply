const initVoiceSupportRecorder = () => {
    const form = document.getElementById('voice-support-form');

    if (! form || form.dataset.recorderReady === 'true') {
        return;
    }

    form.dataset.recorderReady = 'true';

    const nameInput = form.querySelector('[data-voice-name]');
    const emailInput = form.querySelector('[data-voice-email]');
    const panel = form.querySelector('[data-voice-panel]');
    const indicator = form.querySelector('[data-voice-indicator]');
    const button = form.querySelector('[data-voice-button]');
    const statusTitle = form.querySelector('[data-voice-status-title]');
    const statusDetail = form.querySelector('[data-voice-status-detail]');
    const timer = form.querySelector('[data-voice-timer]');
    const csrfToken = form.querySelector('input[name="_token"]')?.value;

    if (! nameInput || ! emailInput || ! panel || ! indicator || ! button || ! statusTitle || ! statusDetail || ! timer) {
        return;
    }

    let recorder = null;
    let stream = null;
    let chunks = [];
    let isRecording = false;
    let isSending = false;
    let recordingStartedAt = null;
    let timerInterval = null;

    const stateStyles = {
        idle: {
            panel: 'rounded-3xl border border-white/10 bg-white/10 p-4 transition',
            indicator: 'mt-1 h-3 w-3 shrink-0 rounded-full bg-stone-500 ring-4 ring-white/10',
        },
        ready: {
            panel: 'rounded-3xl border border-red-300/25 bg-red-500/10 p-4 shadow-lg shadow-red-950/20 transition',
            indicator: 'mt-1 h-3 w-3 shrink-0 rounded-full bg-red-300 ring-4 ring-red-300/20',
        },
        recording: {
            panel: 'rounded-3xl border border-red-300/40 bg-red-500/20 p-4 shadow-lg shadow-red-950/30 transition',
            indicator: 'mt-1 h-3 w-3 shrink-0 animate-pulse rounded-full bg-red-300 ring-4 ring-red-300/30',
        },
        sending: {
            panel: 'rounded-3xl border border-amber-300/40 bg-amber-500/15 p-4 shadow-lg shadow-amber-950/20 transition',
            indicator: 'mt-1 h-3 w-3 shrink-0 animate-pulse rounded-full bg-amber-200 ring-4 ring-amber-200/20',
        },
        success: {
            panel: 'rounded-3xl border border-emerald-300/40 bg-emerald-500/15 p-4 shadow-lg shadow-emerald-950/20 transition',
            indicator: 'mt-1 h-3 w-3 shrink-0 rounded-full bg-emerald-300 ring-4 ring-emerald-300/20',
        },
        error: {
            panel: 'rounded-3xl border border-red-300/50 bg-red-950/35 p-4 shadow-lg shadow-red-950/30 transition',
            indicator: 'mt-1 h-3 w-3 shrink-0 rounded-full bg-red-200 ring-4 ring-red-200/20',
        },
    };

    const formatElapsed = (seconds) => {
        const minutes = Math.floor(seconds / 60).toString().padStart(2, '0');
        const remainder = (seconds % 60).toString().padStart(2, '0');

        return `${minutes}:${remainder}`;
    };

    const stopTimer = () => {
        clearInterval(timerInterval);
        timerInterval = null;
        recordingStartedAt = null;
        timer.classList.add('hidden');
        timer.textContent = '00:00';
    };

    const startTimer = () => {
        stopTimer();
        recordingStartedAt = Date.now();
        timer.classList.remove('hidden');
        timer.textContent = '00:00';
        timerInterval = setInterval(() => {
            timer.textContent = formatElapsed(Math.floor((Date.now() - recordingStartedAt) / 1000));
        }, 1000);
    };

    const setStatus = (state, title, detail) => {
        panel.className = stateStyles[state].panel;
        indicator.className = stateStyles[state].indicator;
        statusTitle.textContent = title;
        statusDetail.textContent = detail;
    };

    const stopStream = () => {
        stream?.getTracks().forEach((track) => track.stop());
        stream = null;
    };

    const formIsValid = () => nameInput.value.trim() !== '' && emailInput.validity.valid;

    const syncButton = () => {
        if (isRecording) {
            button.disabled = false;
            button.textContent = 'Stop and send';

            return;
        }

        if (isSending) {
            button.disabled = true;
            button.textContent = 'Sending...';

            return;
        }

        button.disabled = ! formIsValid();
        button.textContent = 'Start recording';
    };

    const sendRecording = async (audioBlob) => {
        isSending = true;
        syncButton();
        setStatus('sending', 'Sending to support', 'Uploading the audio and creating a transcript.');

        const data = new FormData(form);
        data.append('audio', audioBlob, 'support-message.webm');

        try {
            const response = await fetch(form.dataset.endpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
                body: data,
            });

            const json = await response.json().catch(() => null);

            if (! response.ok) {
                throw new Error(json?.message || 'Could not send the message.');
            }

            form.reset();
            setStatus('success', 'Message received', json?.message || 'Your voice message was sent to the support dashboard.');
        } catch (error) {
            setStatus('error', 'Could not send message', error?.message || 'Please try recording again.');
        } finally {
            isSending = false;
            chunks = [];
            syncButton();
        }
    };

    const startRecording = async () => {
        if (! formIsValid()) {
            setStatus('idle', 'Missing details', 'Please enter a valid name and email first.');
            syncButton();

            return;
        }

        if (! window.isSecureContext) {
            setStatus('error', 'Recording unavailable', 'Audio recording needs HTTPS or localhost.');

            return;
        }

        if (! navigator.mediaDevices?.getUserMedia || ! window.MediaRecorder) {
            setStatus('error', 'Recording unavailable', 'Audio recording is not supported in this browser.');

            return;
        }

        chunks = [];
        button.disabled = true;
        setStatus('sending', 'Waiting for microphone', 'Approve microphone access to start recording.');

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
                const audioBlob = new Blob(chunks, { type: recorder.mimeType || 'audio/webm' });

                isRecording = false;
                recorder = null;
                stopStream();

                void sendRecording(audioBlob);
            });

            recorder.start();
            isRecording = true;
            startTimer();
            setStatus('recording', 'Recording in progress', 'Speak naturally. Press stop when you are finished.');
            syncButton();
        } catch (error) {
            recorder = null;
            isRecording = false;
            stopStream();
            stopTimer();
            setStatus('error', 'Microphone blocked', error?.message || 'Could not access the microphone.');
            syncButton();
        }
    };

    const stopRecording = () => {
        if (recorder && recorder.state !== 'inactive') {
            stopTimer();
            setStatus('sending', 'Preparing message', 'Encoding the audio before upload.');
            recorder.stop();
        }
    };

    button.addEventListener('click', () => {
        if (isSending) {
            return;
        }

        if (isRecording) {
            stopRecording();

            return;
        }

        void startRecording();
    });

    nameInput.addEventListener('input', syncButton);
    emailInput.addEventListener('input', syncButton);
    nameInput.addEventListener('input', () => {
        if (! isRecording && ! isSending) {
            setStatus(formIsValid() ? 'ready' : 'idle', formIsValid() ? 'Ready to record' : 'Ready when you are', formIsValid() ? 'Press start when you want to leave a voice message.' : 'Enter name and email to enable recording.');
        }
    });
    emailInput.addEventListener('input', () => {
        if (! isRecording && ! isSending) {
            setStatus(formIsValid() ? 'ready' : 'idle', formIsValid() ? 'Ready to record' : 'Ready when you are', formIsValid() ? 'Press start when you want to leave a voice message.' : 'Enter name and email to enable recording.');
        }
    });

    setStatus('idle', 'Ready when you are', 'Enter name and email to enable recording.');
    syncButton();
};

const initSupportDraftGeneration = () => {
    document.querySelectorAll('[data-generate-draft-form]').forEach((form) => {
        if (form.dataset.generateReady === 'true') {
            return;
        }

        form.dataset.generateReady = 'true';

        form.addEventListener('submit', () => {
            const button = form.querySelector('[data-generate-draft-button]');
            const idleLabel = form.querySelector('[data-generate-idle]');
            const loadingLabel = form.querySelector('[data-generate-loading]');
            const status = form.parentElement?.querySelector('[data-generate-draft-status]');

            document.querySelectorAll('[data-generate-draft-button]').forEach((generateButton) => {
                generateButton.disabled = true;
            });

            button?.setAttribute('aria-busy', 'true');
            idleLabel?.classList.add('hidden');
            loadingLabel?.classList.remove('hidden');
            loadingLabel?.classList.add('inline-flex');
            status?.classList.remove('hidden');
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initVoiceSupportRecorder();
        initSupportDraftGeneration();
    });
} else {
    initVoiceSupportRecorder();
    initSupportDraftGeneration();
}

document.addEventListener('livewire:navigated', () => {
    initVoiceSupportRecorder();
    initSupportDraftGeneration();
});
