<?php

namespace BiiiiiigMonster\LaravelEnum;

use InvalidArgumentException;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use RuntimeException;
use SplFileInfo;

class Reader
{
    private SplFileInfo $file;

    private Parser $parser;

    private NodeTraverser $traverser;

    private array $visitors;

    /**
     * Reader constructor.
     *
     * @param SplFileInfo $file
     * @param NodeVisitor ...$visitors
     */
    public function __construct(SplFileInfo $file, NodeVisitor ...$visitors)
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();

        $this->file = $file;
        $this->visitors = $visitors;
        $this->read();
    }

    private function read(): void
    {
        if (empty($contents = file_get_contents($this->file->getPathname()))) {
            throw new InvalidArgumentException('file contents get fail.');
        }

        if (is_null($ast = $this->parser->parse($contents))) {
            throw new RuntimeException('file contents parse error.');
        }

        // add visitor.
        foreach ($this->visitors as $visitor) {
            $this->traverser->addVisitor($visitor);
        }
        $this->traverser->traverse($ast);
    }
}
