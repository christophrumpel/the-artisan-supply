<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportVoiceMessageRequest;
use App\Models\SupportMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laravel\Ai\Transcription;

class SupportVoiceMessageController extends Controller
{
    public function __invoke(StoreSupportVoiceMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $audio = $this->storeAudio($request->file('audio'));

        SupportMessage::create([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'subject' => 'Voice support message',
            'message' => 'Voice message submitted from the support page.',
            'audio_path' => $audio['path'],
            'audio_mime_type' => $audio['mime_type'],
            'audio_size' => $audio['size'],
            'transcription' => Transcription::fromPath(public_path($audio['path']))->generate()->text,
        ]);

        return response()->json([
            'message' => 'Voice message received. Our support wizards are listening.',
        ]);
    }

    /**
     * @return array{path: string, mime_type: string|null, size: int}
     */
    private function storeAudio(UploadedFile $audio): array
    {
        $directory = 'uploads/support-audio';
        $mimeType = $audio->getMimeType();
        $filename = Str::uuid().'.'.($audio->guessExtension() ?: 'webm');
        $path = $directory.'/'.$filename;

        File::ensureDirectoryExists(public_path($directory));
        $audio->move(public_path($directory), $filename);

        return [
            'path' => $path,
            'mime_type' => $mimeType,
            'size' => File::size(public_path($path)),
        ];
    }
}
