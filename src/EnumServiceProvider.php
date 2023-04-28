<?php

namespace BiiiiiigMonster\LaravelEnum;

use BiiiiiigMonster\LaravelEnum\Commands\EnumAnnotateCommand;
use BiiiiiigMonster\LaravelEnum\Rules\Enumerate;
use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    const LANG_NAMESPACE = __NAMESPACE__;

    public function boot(): void
    {
        $this->bootCommands();
        $this->bootTranslations();
        $this->bootValidators();
    }

    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(EnumAnnotateCommand::class);
        }
    }

    private function bootValidators(): void
    {
        Validator::extend('enumerate', function ($attribute, $value, $parameters, $validator) {
            return (new Enumerate(...$parameters))->passes($attribute, $value);
        }, trans(self::LANG_NAMESPACE.'::validation.enumerate'));

        Validator::extend('enum_meta', function ($attribute, $value, $parameters, $validator) {
            return (new EnumMeta(...$parameters))->passes($attribute, $value);
        }, trans(self::LANG_NAMESPACE.'::validation.enum_meta'));
    }

    private function bootTranslations(): void
    {
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/laravelEnum'),
        ], 'translations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', self::LANG_NAMESPACE);
    }
}
