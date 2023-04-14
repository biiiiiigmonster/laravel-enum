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

    /**
     * Class is interface.
     */
    private bool $interface = false;

    public function getClass(): string
    {
        return sprintf('%s\\%s', $this->namespace, $this->className);
    }

    public function isInterface(): bool
    {
        return $this->interface;
    }

    /**
     * Enter node
     */
    public function enterNode(Node $node): Node
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name?->toString();
        } elseif ($node instanceof Node\Stmt\Class_) {
            $this->className = $node->name?->toString();
        } elseif ($node instanceof Node\Stmt\Interface_) {
            $this->interface = true;
        }

        return $node;
    }
}
