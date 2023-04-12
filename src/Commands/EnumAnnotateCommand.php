<?php

namespace BiiiiiigMonster\LaravelEnum\Commands;

use BiiiiiigMonster\LaravelEnum\ClassVisitor;
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Reader;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Laminas\Code\Generator\DocBlock\Tag\MethodTag;
use Laminas\Code\Generator\DocBlock\Tag\TagInterface;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Reflection\DocBlockReflection;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Finder\Finder;
use UnitEnum;

#[AsCommand(name: 'enum:annotate')]
class EnumAnnotateCommand extends Command
{
    protected $signature = 'enum:annotate
                            {class?* : The enum class to generate annotations for}
                            {--folder=* : The folder to scan for enums to annotate}';

    protected $description = 'Generate DocBlock annotations of meta method for enum classes';

    /**
     * @throws ReflectionException
     */
    public function handle(): void
    {
        if ($classNames = $this->argument('class')) {
            foreach ($classNames as $className) {
                $reflection = new ReflectionClass($className);
                if (! $reflection->isSubclassOf(UnitEnum::class)) {
                    throw new InvalidArgumentException(
                        sprintf('The given class must be an instance of %s: %s', UnitEnum::class, $className)
                    );
                }

                if (
                    collect($reflection->getTraits())
                        ->doesntContain(fn (ReflectionClass $refTrait) => $refTrait->isSubclassOf(EnumTraits::class))
                ) {
                    throw new InvalidArgumentException(
                        sprintf('The given class must be use trait of %s: %s', EnumTraits::class, $className)
                    );
                }
                $this->annotate($reflection);
            }

            return;
        }

        foreach ($this->getClassFinder() as $file) {
            new Reader($file, $classVisitor = new ClassVisitor());
            $reflection = new ReflectionClass($classVisitor->getClass());
            if ($reflection->isSubclassOf(UnitEnum::class)
                && collect($reflection->getTraits())
                    ->doesntContain(fn (ReflectionClass $refTrait) => $refTrait->isSubclassOf(EnumTraits::class))) {
                $this->annotate($reflection);
            }
        }
    }

    protected function annotate(ReflectionClass $reflection)
    {
        $this->writeDocComment($reflection, $this->getDocBlock($reflection));
    }

    protected function getDocBlock(ReflectionClass $reflection): DocBlockGenerator
    {
        $docBlock = $reflection->getDocComment()
            ? DocBlockGenerator::fromReflection(new DocBlockReflection($reflection))
            : new DocBlockGenerator();

        $retainedTags = collect($docBlock->getTags())
            ->reject(fn (TagInterface $tag) => $tag instanceof MethodTag)
            ->all();

        $tags = collect($reflection->getName()::metaMethods())
            ->map(fn (string $methodName) => new MethodTag($methodName))
            ->merge($retainedTags)
            ->all();

        $docBlock->setTags($tags);

        return $docBlock;
    }

    protected function writeDocComment(ReflectionClass $reflection, DocBlockGenerator $docBlock): void
    {
        $fileName = $reflection->getFileName();
        $shortName = $reflection->getShortName();
        $contents = file_get_contents($fileName);
        $classDeclaration = "class $shortName";

        if ($reflection->isFinal()) {
            $classDeclaration = "final $classDeclaration";
        } elseif ($reflection->isAbstract()) {
            $classDeclaration = "abstract $classDeclaration";
        }

        // Remove existing docblock
        $contents = preg_replace(
            sprintf('#([\n]?\/\*(?:[^*]|\n|(?:\*(?:[^\/]|\n)))*\*\/)?[\n]?%s#ms', preg_quote($classDeclaration)),
            "\n".$classDeclaration,
            $contents
        );

        $classDeclarationOffset = strpos($contents, $classDeclaration);
        // Make sure we don't replace too much
        $contents = substr_replace(
            $contents,
            sprintf('%s%s', $docBlock->generate(), $classDeclaration),
            $classDeclarationOffset,
            strlen($classDeclaration)
        );

        file_put_contents($fileName, $contents);
        $this->info("Wrote new phpDocBlock to {$fileName}.");
    }

    protected function getClassFinder(): Finder
    {
        $finder = new Finder();
        $scanPaths = $this->option('folder') ?? [app_path('Enums')];

        return $finder->files()->in($scanPaths)->name('*.php');
    }
}
