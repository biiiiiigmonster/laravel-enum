<?php

namespace BiiiiiigMonster\LaravelEnum;

use BiiiiiigMonster\LaravelEnum\Commands\EnumAnnotateCommand;
use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;
use BiiiiiigMonster\LaravelEnum\Rules\EnumName;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootTranslations();
        $this->bootValidators();
    }

    private function bootCommands(): void
    {
        if ($this->app->runningInConsole() && ($this->app->isLocal() || $this->app->runningUnitTests())) {
            $this->commands(EnumAnnotateCommand::class);
        }
    }

    private function bootValidators(): void
    {
        Validator::extend('enum_name', function ($attribute, $value, $parameters, $validator) {
            return (new EnumName(...$parameters))->passes($attribute, $value);
        });

        Validator::extend('enum_meta', function ($attribute, $value, $parameters, $validator) {
            return (new EnumMeta(...$parameters))->passes($attribute, $value);
        });
    }

    private function bootTranslations(): void
    {
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/laravelEnum'),
        ], 'translations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravelEnum');
    }
}
