<?php

namespace BiiiiiigMonster\LaravelEnum;

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
        if ($this->app->runningInConsole()) {
            $this->commands(EnumPhpdocCommand::class);
        }
    }

    private function bootValidators(): void
    {
        echo '看看enum字符串：'.trans(static::LANG_NAMESPACE.'::validation.enum');
        Validator::extend('enum', function ($attribute, $value, $parameters, $validator) {
            return (new Enum(...$parameters))->passes($attribute, $value);
        }, trans(static::LANG_NAMESPACE.'::validation.enum'));

        echo '看看enum_meta字符串：'.trans(static::LANG_NAMESPACE.'::validation.enum_meta');
        Validator::extend('enum_meta', function ($attribute, $value, $parameters, $validator) {
            return (new EnumMeta(...$parameters))->passes($attribute, $value);
        }, trans(static::LANG_NAMESPACE.'::validation.enum_meta'));
    }

    private function bootTranslations(): void
    {
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/'.static::LANG_NAMESPACE),
        ], 'translations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', static::LANG_NAMESPACE);
    }
}
