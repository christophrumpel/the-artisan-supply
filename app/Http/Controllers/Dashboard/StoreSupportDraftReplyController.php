<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreSupportDraftReplyController extends Controller
{
    public function __invoke(Request $request, SupportMessage $supportMessage): RedirectResponse
    {
        if ($supportMessage->draft_reply !== null) {
            return back()->with('status', 'This email already has a draft reply.');
        }

        $validated = $request->validate([
            'draft_reply' => ['required', 'string', 'max:3000'],
        ]);

        $supportMessage->update([
            'draft_reply' => $validated['draft_reply'],
        ]);

        return back()->with('status', "Draft reply added to {$supportMessage->customer_name}'s email.");
    }
}
