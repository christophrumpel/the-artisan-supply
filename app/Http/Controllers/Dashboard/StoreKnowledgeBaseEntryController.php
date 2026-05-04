<?php

namespace App\Http\Controllers\Dashboard;

use App\Ai\KnowledgeBaseEmbeddings;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreKnowledgeBaseEntryController extends Controller
{
    public function __invoke(Request $request, KnowledgeBaseEmbeddings $embeddings): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:4000'],
        ]);

        $faq = Faq::create([
            'question' => $validated['title'],
            'answer' => $validated['text'],
        ]);

        $embeddings->embed($faq);

        return back()->with('status', 'Knowledge base entry added.');
    }
}
