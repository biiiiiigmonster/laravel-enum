<?php

namespace BiiiiiigMonster\LaravelEnum;

use BiiiiiigMonster\Aop\Concerns\MethodTrait;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\NodeVisitorAbstract;

class ClassVisitor extends NodeVisitorAbstract
{
    /**
     * Class namespace
     * @var string
     */
    private string $namespace = '';

    /**
     * Class name
     * @var string
     */
    private string $className = '';

    /**
     * Class is interface.
     * @var bool
     */
    private bool $interface = false;

    /**
     * @return string
     */
    public function getClass(): string
    {
        return sprintf('%s\\%s', $this->namespace, $this->className);
    }

    /**
     * @return bool
     */
    public function isInterface(): bool
    {
        return $this->interface;
    }

    /**
     * Enter node
     *
     * @param Node $node
     * @return Node
     */
    public function enterNode(Node $node): Node
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        } elseif ($node instanceof Node\Stmt\Class_) {
            $this->className = $node->name->toString();
        } elseif ($node instanceof Node\Stmt\Interface_) {
            $this->interface = true;
        }
        return $node;
    }

    /**
     * @param Node $node
     * @return Node
     */
    public function leaveNode(Node $node): Node
    {
        if (
            $node instanceof Node\Stmt\Trait_
            || ($node instanceof Class_ && !$node->isAnonymous())
        ) {
            array_unshift($node->stmts, new TraitUse([
                new Node\Name('\\' . MethodTrait::class)
            ]));
        }

        return $node;
    }
}
