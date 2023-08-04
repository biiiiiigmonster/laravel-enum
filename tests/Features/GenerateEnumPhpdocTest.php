<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Illuminate\Filesystem\Filesystem;
use Mockery\MockInterface;

it('can generate method docComment for enums', function () {
    $filePutContents = '';
    $this->partialMock(Filesystem::class, function (MockInterface $mock) use (&$filePutContents) {
        $mock->shouldReceive('put')
            ->andReturnUsing(function ($path, $contents) use (&$filePutContents) {
                $filePutContents .= $contents;

                return file_put_contents($path, $contents);
            });
    });

    $this->artisan('enum:phpdoc', ['enum' => Week::class]);

    expect($filePutContents)
        ->toContain(
            '@method static int SUNDAY()',
            '@method static int SATURDAY()',
            '@method static int MONDAY()',
            '@method mixed description()',
            '@method string help()',
        );
});
