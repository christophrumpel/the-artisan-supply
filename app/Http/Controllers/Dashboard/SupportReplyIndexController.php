<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\View\View;

class SupportReplyIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard.support-replies', [
            'supportMessages' => SupportMessage::query()
                ->whereNotNull('audio_path')
                ->latest()
                ->get(),
        ]);
    }
}
