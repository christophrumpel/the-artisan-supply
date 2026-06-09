<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Mcp\Client\OAuth\TokenSet;
use Laravel\Mcp\Facades\Mcp;

Mcp::oAuthRoutesFor('nightwatch',
    function (string $provider, TokenSet $token) {
        $expiresAt = $token->expiresAt !== null
            ? Carbon::createFromTimestamp($token->expiresAt)
            : now()->addHour();

        Cache::put('mcp_nightwatch_token', $token->accessToken, $expiresAt);

        return redirect()->route('dashboard')->with('status', "{$provider} connected.");
    },
    middleware: ['web', 'auth', 'verified'],
);
