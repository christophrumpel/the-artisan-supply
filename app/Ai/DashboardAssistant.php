<?php

namespace App\Ai;

use App\Ai\Tools\ShopMetricsTool;
use Laravel\Ai\Responses\AgentResponse;

use function Laravel\Ai\agent;

class DashboardAssistant
{
    public function prompt(string $message): AgentResponse
    {
        return agent(
            instructions: <<<'INSTRUCTIONS'
You are the backend assistant for The Artisan Supply dashboard.

Answer shopkeeper questions briefly and clearly. Use the available tools when the user asks about current shop data, counts, support workload, assets, products, inventory, or FAQs. Do not invent database numbers.
INSTRUCTIONS,
            tools: [
                new ShopMetricsTool,
            ],
        )->prompt($message);
    }
}
