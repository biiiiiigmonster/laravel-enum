<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Illuminate\Filesystem\Filesystem;

it('can generate method docComment for enums', function () {
    $filePutContents = '';
    $mockFilesystem = Mockery::mock(Filesystem::class);
    $mockFilesystem
        ->shouldReceive('get')
        ->andReturnUsing(function ($file) {
            return file_get_contents($file);
        });

    $mockFilesystem
        ->shouldReceive('put')
        ->with(Mockery::any(), Mockery::any())
        ->andReturnUsing(function ($path, $contents) use (&$filePutContents) {
            $filePutContents .= $contents;

            return strlen($contents);
        });
    $this->instance(Filesystem::class, $mockFilesystem);
    $this->artisan('enum:annotate', ['enum' => Week::class]);

    expect($filePutContents)
        ->toContain('@method static int SUNDAY()')
        ->toContain('@method static int SATURDAY()')
        ->toContain('@method mixed description()')
        ->toContain('@method string help()');
});
