<?php

use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;
use BiiiiiigMonster\LaravelEnum\Rules\EnumName;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Color;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Illuminate\Support\Facades\Validator;

it('validate failed message when the enum case name not match', function () {
    $param = [
        'today' => 'MONDAY',
    ];
    $ruler = [
        'today' => ['required', new EnumName(Week::class)],
    ];
    $messages = Validator::make($param, $ruler)->messages()->messages();

    expect($messages)
        ->toHaveKey('today')
        ->and($messages['today'])
        ->toContain('The selected name today is invalid.');
});

it('validate failed message when the enum case name match, using string pipe validation', function () {
    $param = [
        'today' => 'MONDAY',
    ];
    $ruler = [
        'today' => 'required|enum_name:'.Week::class,
    ];
    $messages = Validator::make($param, $ruler)->messages()->messages();

    expect($messages)
        ->toHaveKey('today')
        ->and($messages['today'])
        ->toContain('The selected name today is invalid.');
});

it('validate failed message when the enum have not meta class match', function () {
    $param = [
        'color' => 'gray',
    ];
    $ruler = [
        'color' => ['required', new EnumMeta(Week::class, Color::class)],
    ];
    $messages = Validator::make($param, $ruler)->messages()->messages();

    expect($messages)
        ->toHaveKey('color')
        ->and($messages['color'])
        ->toContain('The selected meta color is invalid.');
});

it('validate failed message when the enum case meta properties matches, using string pipe validation', function () {
    $param = [
        'color' => 'gray',
    ];
    $ruler = [
        'color' => 'required|enum_meta:'.Week::class.','.Color::class,
    ];

    $messages = Validator::make($param, $ruler)->messages()->messages();

    expect($messages)
        ->toHaveKey('color')
        ->and($messages['color'])
        ->toContain('The selected meta color is invalid.');
});
