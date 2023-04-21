<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Laminas\Code\Generator\DocBlock\Tag\MethodTag;
use Laminas\Code\Generator\DocBlock\Tag\TagInterface;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Reflection\DocBlockReflection;

test('can generate method docComment for enums', function () {
    $this->artisan('enum:annotate', ['enum' => Week::class]);
    $docBlock = DocBlockGenerator::fromReflection(new DocBlockReflection(new ReflectionEnum(Week::class)));
    $docMethods = collect($docBlock->getTags())
        ->filter(fn (TagInterface $tag) => $tag instanceof MethodTag)
        ->map(fn (MethodTag $tag) => $tag->getMethodName())
        ->all();

    expect($docMethods)->toBe(['SUNDAY', 'SATURDAY', 'description', 'help']);
});
