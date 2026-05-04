<?php

namespace App\Ai;

use App\Models\Faq;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Embeddings;
use Throwable;

class KnowledgeBaseEmbeddings
{
    public function embed(Faq $faq): bool
    {
        $embedding = $this->forText($this->textFor($faq));

        if ($embedding === null) {
            return false;
        }

        $faq->forceFill([
            'embedding' => $embedding,
            'embedding_model' => $this->embeddingModel(),
        ])->save();

        return true;
    }

    /**
     * @return array<float>|null
     */
    public function forQuery(string $query): ?array
    {
        return $this->forText($query);
    }

    public function textFor(Faq $faq): string
    {
        return trim("Question: {$faq->question}\nAnswer: {$faq->answer}");
    }

    /**
     * @return array<float>|null
     */
    private function forText(string $text): ?array
    {
        if (blank($text) || ! $this->canGenerateEmbeddings()) {
            return null;
        }

        try {
            return Embeddings::for([$text])
                ->cache()
                ->generate()
                ->first();
        } catch (Throwable $exception) {
            Log::warning('Knowledge base embedding generation failed.', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function canGenerateEmbeddings(): bool
    {
        $provider = config('ai.default_for_embeddings');
        $key = config("ai.providers.{$provider}.key");

        if (app()->runningUnitTests()) {
            return Embeddings::isFaked();
        }

        return filled($provider) && (filled($key) || in_array($provider, ['ollama'], true));
    }

    private function embeddingModel(): string
    {
        $provider = config('ai.default_for_embeddings');

        return config("ai.providers.{$provider}.models.embeddings.default")
            ?? config("ai.providers.{$provider}.embedding_deployment")
            ?? 'default';
    }
}
