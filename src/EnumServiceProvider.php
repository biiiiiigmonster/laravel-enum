<?php

namespace BiiiiiigMonster\LaravelEnum;

use BiiiiiigMonster\LaravelEnum\Commands\EnumMakeCommand;
use BiiiiiigMonster\LaravelEnum\Commands\EnumMetaMakeCommand;
use BiiiiiigMonster\LaravelEnum\Commands\EnumPhpdocCommand;
use BiiiiiigMonster\LaravelEnum\Rules\Enum;
use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    const LANG_NAMESPACE = 'enum';

    public function boot(): void
    {
        $this->bootCommands();
        $this->bootTranslations();
        $this->bootValidators();
    }

    private function bootCommands(): void
    {
        $this->publishes([
            __DIR__.'/Commands/stubs' => $this->app->basePath('stubs'),
        ], 'stubs');

        if ($this->app->runningInConsole()) {
            $this->commands([
                EnumPhpdocCommand::class,
                EnumMakeCommand::class,
                EnumMetaMakeCommand::class,
            ]);
        }
    }

    private function bootValidators(): void
    {
        Validator::extend('enumerate', function ($attribute, $value, $parameters, $validator) {
            return (new Enum(...$parameters))->passes($attribute, $value);
        }, trans(static::LANG_NAMESPACE.'::validations.enumerate'));

        Validator::extend('enum_meta', function ($attribute, $value, $parameters, $validator) {
            return (new EnumMeta(...$parameters))->passes($attribute, $value);
        }, trans(static::LANG_NAMESPACE.'::validations.enum_meta'));
    }

    private function bootTranslations(): void
    {
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/'.static::LANG_NAMESPACE),
        ], 'translations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', static::LANG_NAMESPACE);
    }
}
