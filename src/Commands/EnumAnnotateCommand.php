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
use ReflectionEnum;
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

    public function handle(): void
    {
        if ($classNames = (array) $this->argument('class')) {
            foreach ($classNames as $className) {
                /** @var class-string $className */
                if (! enum_exists($className)) {
                    throw new InvalidArgumentException(
                        sprintf('The given class must be an instance of %s: %s', UnitEnum::class, $className)
                    );
                }

                if (! in_array(EnumTraits::class, class_uses_recursive($className))) {
                    throw new InvalidArgumentException(
                        sprintf('The given class must be use trait of %s: %s', EnumTraits::class, $className)
                    );
                }
                $this->annotate($className);
            }

            return;
        }

        foreach ($this->getClassFinder() as $file) {
            new Reader($file, $classVisitor = new ClassVisitor());
            $className = $classVisitor->getName();
            if (enum_exists($className) && in_array(EnumTraits::class, class_uses_recursive($className))) {
                $this->annotate($className);
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    protected function annotate(string $className): void
    {
        $reflection = new ReflectionEnum($className);

        $this->writeDocComment($reflection, $this->getDocBlock($reflection));
    }

    protected function getDocBlock(ReflectionEnum $reflection): DocBlockGenerator
    {
        $docBlock = $reflection->getDocComment()
            ? DocBlockGenerator::fromReflection(new DocBlockReflection($reflection->getDocComment()))
            : new DocBlockGenerator();

        $retainedTags = collect($docBlock->getTags())
            ->reject(fn (TagInterface $tag) => $tag instanceof MethodTag);

        $tags = collect($reflection->getName()::metaMethods())
            ->map(fn (string $methodName) => new MethodTag($methodName))
            ->merge($retainedTags)
            ->all();

        $docBlock->setTags($tags);

        return $docBlock;
    }

    protected function writeDocComment(ReflectionEnum $reflection, DocBlockGenerator $docBlock): void
    {
        $fileName = (string) $reflection->getFileName();
        $shortName = $reflection->getShortName();
        $contents = (string) file_get_contents($fileName);
        $classDeclaration = "enum $shortName";

        // Remove existing docblock
        $contents = (string) preg_replace(
            sprintf('#([\n]?\/\*(?:[^*]|\n|(?:\*(?:[^\/]|\n)))*\*\/)?[\n]?%s#ms', preg_quote($classDeclaration)),
            "\n".$classDeclaration,
            $contents
        );

        $classDeclarationOffset = (int) strpos($contents, $classDeclaration);
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
        $scanPaths = $this->option('folder') ?? app_path('Enums');

        return $finder->files()->in((array) $scanPaths)->name('*.php');
    }
}
