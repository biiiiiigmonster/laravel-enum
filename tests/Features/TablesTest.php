<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\EmptyCase;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can return an indexed array of tables from a backed enum', function () {
    foreach (Status::tables() as $table) {
        expect($table)
            ->toBeArray()
            ->toHaveKeys(['name', 'value', 'color', 'description', 'help']);
    }
});

it('can return an indexed array of tables from a pure enum', function () {
    foreach (Role::tables() as $table) {
        expect($table)
            ->toBeArray()
            ->toHaveKeys(['name', 'color', 'description', 'help']);
    }
});

it('can return an empty tables array from an enum of empty case')
    ->expect(EmptyCase::tables())
    ->toBeEmpty();
