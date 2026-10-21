<?php

use Victorycodedev\ToastKit\Enums\ToastPresentation;
use Victorycodedev\ToastKit\Exceptions\InvalidToastConfigurationException;
use Victorycodedev\ToastKit\PendingToast;

it('uses standard presentation by default', function () {
    expect($this->kit->make('Saved')->payload()['presentation'])->toBe('standard');
});

it('selects a presentation by string or enum', function (ToastPresentation|string $presentation, string $expected) {
    expect($this->kit->make('Saved')->presentation($presentation)->payload()['presentation'])->toBe($expected);
})->with([
    ['standard', 'standard'],
    ['dynamic', 'dynamic'],
    [ToastPresentation::Standard, 'standard'],
    [ToastPresentation::Dynamic, 'dynamic'],
]);

it('provides a dynamic presentation shortcut', function () {
    expect($this->kit->make('Saved')->dynamic()->payload()['presentation'])->toBe('dynamic');
});

it('rejects an invalid presentation', function () {
    expect(fn () => $this->kit->make('Saved')->presentation('floating'))
        ->toThrow(InvalidToastConfigurationException::class);
});

it('sends presentation as a sparse update', function () {
    $this->kit->update('upload')->dynamic()->show();

    expect($this->calls[0][1]['changes'])->toBe(['presentation' => 'dynamic']);
});

it('supports presentation in presets without leaking state', function () {
    $this->kit->definePreset('dynamic-status', fn (PendingToast $toast) => $toast->dynamic());

    expect($this->kit->preset('dynamic-status')->message('First')->payload()['presentation'])->toBe('dynamic')
        ->and($this->kit->make('Second')->payload()['presentation'])->toBe('standard');
});
