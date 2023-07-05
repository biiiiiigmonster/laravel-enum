<?php

namespace BiiiiiigMonster\LaravelEnum;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ClassVisitor extends NodeVisitorAbstract
{
    /**
     * Class namespace
     */
    private ?string $namespace = null;

    /**
     * Class name
     */
    private ?string $className = null;

    public function getName(): string
    {
        return sprintf('%s\\%s', $this->namespace, $this->className);
    }

    /**
     * Enter node
     */
    public function enterNode(Node $node): Node
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name?->toString();
        } elseif ($node instanceof Node\Stmt\ClassLike) {
            $this->className = $node->name?->toString();
        }

        return $node;
    }
}
