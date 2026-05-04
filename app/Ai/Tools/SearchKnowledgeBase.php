<?php

namespace App\Ai\Tools;

use App\Ai\KnowledgeBaseEmbeddings;
use App\Models\Faq;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchKnowledgeBase implements Tool
{
    public function __construct(
        private readonly ?KnowledgeBaseEmbeddings $embeddings = null,
    ) {}

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search The Artisan Supply knowledge base for FAQ answers that may help draft customer support replies.';
    }

    public function name(): string
    {
        return 'search_knowledge_base';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = trim((string) ($request['query'] ?? ''));

        if ($query === '') {
            return '[]';
        }

        $queryEmbedding = $this->embeddingService()->forQuery($query);

        if ($queryEmbedding !== null) {
            $results = $this->vectorResults($queryEmbedding);

            if ($results !== []) {
                return json_encode($results, JSON_THROW_ON_ERROR);
            }
        }

        $terms = $this->searchTerms($query);

        if ($terms === []) {
            return '[]';
        }

        return json_encode($this->keywordResults($terms), JSON_THROW_ON_ERROR);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema
                ->string()
                ->description('The customer question or topic to search for in the knowledge base.')
                ->required(),
        ];
    }

    /**
     * @param  list<float>  $queryEmbedding
     * @return list<array{question: string, answer: string, similarity: float, search_method: string}>
     */
    private function vectorResults(array $queryEmbedding): array
    {
        return Faq::query()
            ->select(['question', 'answer', 'embedding'])
            ->whereNotNull('embedding')
            ->get()
            ->map(function (Faq $faq) use ($queryEmbedding): ?array {
                $similarity = $this->cosineSimilarity($queryEmbedding, $faq->embedding ?? []);

                if ($similarity < 0.35) {
                    return null;
                }

                return [
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'similarity' => round($similarity, 4),
                    'search_method' => 'vector',
                ];
            })
            ->filter()
            ->sortByDesc('similarity')
            ->take(5)
            ->values()
            ->all();
    }

    /**
     * @param  list<string>  $terms
     * @return list<array{question: string, answer: string, search_method: string}>
     */
    private function keywordResults(array $terms): array
    {
        $results = Faq::query()
            ->select(['question', 'answer'])
            ->where(function ($query) use ($terms): void {
                foreach ($terms as $term) {
                    $likeTerm = '%'.$this->escapeLike($term).'%';

                    $query
                        ->orWhere('question', 'like', $likeTerm)
                        ->orWhere('answer', 'like', $likeTerm);
                }
            })
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Faq $faq): array => [
                'question' => $faq->question,
                'answer' => $faq->answer,
                'search_method' => 'keyword',
            ])
            ->values()
            ->all();

        return $results;
    }

    /**
     * @return list<string>
     */
    private function searchTerms(string $query): array
    {
        preg_match_all('/[a-z0-9]+/i', mb_strtolower($query), $matches);

        return collect($matches[0])
            ->filter(fn (string $term): bool => mb_strlen($term) >= 3)
            ->reject(fn (string $term): bool => in_array($term, $this->stopWords(), true))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    private function stopWords(): array
    {
        return [
            'about',
            'and',
            'are',
            'can',
            'does',
            'for',
            'how',
            'not',
            'the',
            'why',
            'with',
        ];
    }

    private function escapeLike(string $term): string
    {
        return addcslashes($term, '\\%_');
    }

    /**
     * @param  list<float>  $left
     * @param  list<float>  $right
     */
    private function cosineSimilarity(array $left, array $right): float
    {
        $dimensions = min(count($left), count($right));

        if ($dimensions === 0) {
            return 0.0;
        }

        $dotProduct = 0.0;
        $leftMagnitude = 0.0;
        $rightMagnitude = 0.0;

        for ($index = 0; $index < $dimensions; $index++) {
            $leftValue = (float) $left[$index];
            $rightValue = (float) $right[$index];

            $dotProduct += $leftValue * $rightValue;
            $leftMagnitude += $leftValue ** 2;
            $rightMagnitude += $rightValue ** 2;
        }

        if ($leftMagnitude == 0.0 || $rightMagnitude == 0.0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($leftMagnitude) * sqrt($rightMagnitude));
    }

    private function embeddingService(): KnowledgeBaseEmbeddings
    {
        return $this->embeddings ?? app(KnowledgeBaseEmbeddings::class);
    }
}
