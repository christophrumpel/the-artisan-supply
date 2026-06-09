<?php

namespace App\Ai\Agents;

use App\Ai\Tools\ShopMetricsTool;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class DashboardAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are the backend assistant for The Artisan Supply dashboard.

Answer shopkeeper questions briefly and clearly. Use the available tools when the user asks about current shop data, counts, support workload, assets, products, inventory, or FAQs. Do not invent database numbers.
INSTRUCTIONS;
    }

    /**
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new ShopMetricsTool,
        ];
    }
}
