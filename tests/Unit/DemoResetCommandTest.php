<?php

use App\Models\Faq;
use App\Models\Product;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class);

test('the demo reset command refreshes and seeds the demo database', function () {
    $this->artisan('demo:reset')
        ->expectsOutput('Resetting the demo database.')
        ->expectsOutput('Demo reset complete.')
        ->assertExitCode(0);

    expect(Product::count())->toBe(4)
        ->and(Faq::count())->toBe(3)
        ->and(SupportMessage::count())->toBe(2)
        ->and(User::where('email', 'christoph@test.com')->exists())->toBeTrue();
});

test('the demo reset command runs when destructive commands are prohibited', function () {
    DB::prohibitDestructiveCommands(true);

    $this->artisan('demo:reset')
        ->expectsOutput('Resetting the demo database.')
        ->expectsOutput('Demo reset complete.')
        ->assertExitCode(0);

    expect(Product::count())->toBe(4)
        ->and(Faq::count())->toBe(3)
        ->and(SupportMessage::count())->toBe(2)
        ->and(User::where('email', 'christoph@test.com')->exists())->toBeTrue();
});
