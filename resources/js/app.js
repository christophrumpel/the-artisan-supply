const initVoiceSupportRecorder = () => {
    const form = document.getElementById('voice-support-form');

    if (! form || form.dataset.recorderReady === 'true') {
        return;
    }

    form.dataset.recorderReady = 'true';

    const nameInput = form.querySelector('[data-voice-name]');
    const emailInput = form.querySelector('[data-voice-email]');
    const button = form.querySelector('[data-voice-button]');
    const status = form.querySelector('[data-voice-status]');
    const csrfToken = form.querySelector('input[name="_token"]')?.value;

    if (! nameInput || ! emailInput || ! button || ! status) {
        return;
    }

    let recorder = null;
    let stream = null;
    let chunks = [];
    let isRecording = false;
    let isSending = false;

    const setStatus = (message) => {
        status.textContent = message;
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
        setStatus('Sending voice message...');

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
            setStatus(json?.message || 'Voice message sent.');
        } catch (error) {
            setStatus(error?.message || 'Could not send the message. Please try again.');
        } finally {
            isSending = false;
            chunks = [];
            syncButton();
        }
    };

    const startRecording = async () => {
        if (! formIsValid()) {
            setStatus('Please enter a valid name and email first.');
            syncButton();

            return;
        }

        if (! window.isSecureContext) {
            setStatus('Audio recording needs HTTPS or localhost.');

            return;
        }

        if (! navigator.mediaDevices?.getUserMedia || ! window.MediaRecorder) {
            setStatus('Audio recording is not supported in this browser.');

            return;
        }

        chunks = [];
        button.disabled = true;
        setStatus('Asking for microphone access...');

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
            setStatus('Recording...');
            syncButton();
        } catch (error) {
            recorder = null;
            isRecording = false;
            stopStream();
            setStatus(error?.message || 'Could not access the microphone.');
            syncButton();
        }
    };

    const stopRecording = () => {
        if (recorder && recorder.state !== 'inactive') {
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

    syncButton();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initVoiceSupportRecorder);
} else {
    initVoiceSupportRecorder();
}

document.addEventListener('livewire:navigated', initVoiceSupportRecorder);
