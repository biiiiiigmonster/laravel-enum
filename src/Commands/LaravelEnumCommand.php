<?php

namespace BiiiiiigMonster\\LaravelEnum\LaravelEnum\Commands;

use Illuminate\Console\Command;

class LaravelEnumCommand extends Command
{
    public $signature = 'laravel-enum';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
