<?php

use Victorycodedev\ToastKit\Events\ToastActionPressed;
use Victorycodedev\ToastKit\Events\ToastPressed;
use Victorycodedev\ToastKit\Exceptions\InvalidToastConfigurationException;

it('does not add data to existing toast payloads unless configured', function () {
    expect($this->kit->info('Existing toast')->payload())->not->toHaveKey('data');
});

it('attaches structured data to a toast', function () {
    $payload = $this->kit->info('New message')->data([
        'conversation_id' => 42,
        'message' => ['id' => 781, 'unread' => true],
    ])->payload();

    expect($payload['data'])->toBe([
        'conversation_id' => 42,
        'message' => ['id' => 781, 'unread' => true],
    ]);
});

it('replaces attached data through a sparse update', function () {
    $this->kit->update('message-toast')->data(['conversation_id' => 43])->show();

    expect($this->calls[0][1]['changes'])->toBe([
        'data' => ['conversation_id' => 43],
    ]);
});

it('allows attached data to be cleared', function () {
    expect($this->kit->make('Saved')->data([])->payload()['data'])->toBe([]);
});

it('rejects data that cannot be encoded as JSON', function () {
    $resource = fopen('php://memory', 'r');

    try {
        expect(fn() => $this->kit->make('x')->data(['stream' => $resource]))
            ->toThrow(InvalidToastConfigurationException::class);
    } finally {
        fclose($resource);
    }
});

it('exposes attached data on press events', function () {
    $event = new ToastPressed('toast-one', ['conversation_id' => 42]);

    expect($event->toastId)->toBe('toast-one')
        ->and($event->data)->toBe(['conversation_id' => 42]);
});

it('adds data to action events without breaking the old constructor', function () {
    $legacy = new ToastActionPressed('toast-one', 'retry');
    $event = new ToastActionPressed('toast-two', 'reply', ['message_id' => 781]);

    expect($legacy->data)->toBe([])
        ->and($event->data)->toBe(['message_id' => 781]);
});
