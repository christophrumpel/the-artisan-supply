<?php

namespace App\Ai\Agents;

use App\Models\Faq;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class SupportReplyAgent implements Agent, Conversational
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $knowledgeBase = Faq::query()
            ->latest()
            ->get(['question', 'answer'])
            ->map(fn (Faq $faq): string => "- {$faq->question}\n  {$faq->answer}")
            ->implode("\n");

        $knowledgeBase = $knowledgeBase !== '' ? $knowledgeBase : 'No entries available.';

        return <<<PROMPT
            You write concise customer support email drafts for The Artisan Supply.
            Use only the supplied customer message, transcript, and knowledge base.
            If the knowledge base does not answer the question, say that the team will check and follow up.
            Do not invent product behavior, shipping promises, discounts, or policies.
            Keep the reply friendly, practical, and ready to send.
            Start with a greeting using the customer's first name when available.
            End with "The Artisan Supply team".
            Return only the email body.

            Knowledge base:
            {$knowledgeBase}
        PROMPT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }
}
