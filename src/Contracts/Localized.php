<?php

namespace BiiiiiigMonster\LaravelEnum\Contracts;

interface Localized
{
    public function getLocalizationKey(mixed $value): string;
}