<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\View\View;

class KnowledgeBaseIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard.knowledge-base', [
            'faqs' => Faq::latest()->get(),
        ]);
    }
}
