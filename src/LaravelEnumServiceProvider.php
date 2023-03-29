<?php

namespace BiiiiiigMonster\\LaravelEnum\LaravelEnum;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use BiiiiiigMonster\\LaravelEnum\LaravelEnum\Commands\LaravelEnumCommand;

class LaravelEnumServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-enum')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-enum_table')
            ->hasCommand(LaravelEnumCommand::class);
    }
}
