<?php

namespace BiiiiiigMonster\LaravelEnum;

use BiiiiiigMonster\LaravelEnum\Commands\EnumAnnotateCommand;
use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;
use BiiiiiigMonster\LaravelEnum\Rules\EnumName;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootCommands();
        $this->bootTranslations();
        $this->bootValidators();
    }

    private function bootCommands()
    {
        if ($this->app->runningInConsole() && $this->app->isLocal()) {
            $this->commands(EnumAnnotateCommand::class);
        }
    }

    private function bootValidators()
    {
        Validator::extend('enum_name', function ($attribute, $value, $parameters, $validator) {
            $enum = $parameters[0] ?? null;

            return (new EnumName($enum))->passes($attribute, $value);
        });

        Validator::extend('enum_meta', function ($attribute, $value, $parameters, $validator) {
            $enum = $parameters[0] ?? null;

            $meta = $parameters[1] ?? null;

            return (new EnumMeta($enum, $meta))->passes($attribute, $value);
        });
    }

    private function bootTranslations()
    {
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/laravelEnum'),
        ], 'translations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravelEnum');
    }
}
