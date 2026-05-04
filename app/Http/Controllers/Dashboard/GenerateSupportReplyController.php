<?php

namespace App\Http\Controllers\Dashboard;

use App\Ai\Agents\SupportReplyAgent;
use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;

class GenerateSupportReplyController extends Controller
{
    public function __invoke(SupportMessage $supportMessage): RedirectResponse
    {
        $response = SupportReplyAgent::make()->prompt($this->promptFor($supportMessage));

        $supportMessage->update([
            'draft_reply' => $response->text,
        ]);

        return back()->with('status', 'Generated draft message');
    }

    private function promptFor(SupportMessage $supportMessage): string
    {
        $customerText = $supportMessage->transcription ?: $supportMessage->message;

        return <<<PROMPT
            Customer:
            Name: {$supportMessage->customer_name}
            Email: {$supportMessage->customer_email}
            Subject: {$supportMessage->subject}

            Message:
            {$customerText}
        PROMPT;
    }
}
