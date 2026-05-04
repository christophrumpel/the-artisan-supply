<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function __invoke(): View
    {
        return view('shop.support', [
            'questions' => [
                [
                    'question' => 'Does the Artisan Wand run real commands?',
                    'answer' => 'Not yet. For legal and emotional reasons it is currently decorative only.',
                ],
                [
                    'question' => 'Can I return the Migration Time Machine?',
                    'answer' => 'Yes, but only before you purchased it. Time travel rules are strict.',
                ],
                [
                    'question' => 'Do you ship failed jobs separately?',
                    'answer' => 'We retry them three times, then send a very apologetic postcard.',
                ],
            ],
        ]);
    }
}
