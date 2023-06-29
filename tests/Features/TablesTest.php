<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;
use Pest\Expectation;

it('can return an indexed array of tables from a backed enum', function () {
    foreach (Status::tables() as $table) {
        expect($table)
            ->toBeArray()
            ->when($table['name'] == 'PENDING',
                fn (Expectation $table) => $table->toHaveKeys(['name', 'value', 'color', 'description']))
            ->when($table['name'] == 'DONE',
                fn (Expectation $table) => $table->toHaveKeys(['name', 'value', 'color', 'description', 'help']));
    }
});

it('can return an indexed array of tables from a pure enum', function () {
    foreach (Role::tables() as $table) {
        expect($table)
            ->toBeArray()
            ->toHaveKeys(['name', 'color', 'description', 'help']);
    }
});
