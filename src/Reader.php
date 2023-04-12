<?php

namespace BiiiiiigMonster\LaravelEnum;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use SplFileInfo;

class Reader
{
    private SplFileInfo $file;
    private Parser $parser;
    private NodeTraverser $traverser;
    private array $visitors;

    /**
     * Proxy constructor.
     * @param SplFileInfo $file
     * @param array $visitors
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
        $ast = $this->parser->parse(
            // original code.
            file_get_contents($this->file->getPathname())
        );

        // add visitor.
        foreach ($this->visitors as $visitor) {
            $this->traverser->addVisitor($visitor);
        }
        $this->traverser->traverse($ast);
    }
}
