<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\ProductAsset;
use App\Models\SupportMessage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'assetCount' => ProductAsset::count(),
            'supportMessageCount' => SupportMessage::count(),
            'draftedReplyCount' => SupportMessage::query()->whereNotNull('draft_reply')->count(),
            'faqCount' => Faq::count(),
        ]);
    }
}
