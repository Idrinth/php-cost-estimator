<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class CallStackBuilder extends NodeVisitorAbstract
{
    private string $namespace = '';
    private string $class = '';
    private string $context;

    public function __construct(private readonly CallableList $callableList)
    {
    }

    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        }
        if ($node instanceof Node\Stmt\ClassLike && $node->name instanceof Node\Identifier) {
            $this->class = $node->name->toString();
        }
        if ($node instanceof Node\Expr\FuncCall) {
            $this->callableList->registerCallee($this->context, $this->namespace . '\\' . $node->name->toString(), 1);
        }
        if ($node instanceof Node\Stmt\ClassMethod && $node->name instanceof Node\Identifier) {
            $this->context = $this->namespace . '\\' . $this->class . '::' . $node->name->toString();
        }
        if ($node instanceof Node\Stmt\Function_ && $node->name instanceof Node\Identifier) {
            $this->context = $this->namespace . '\\' . $node->name->toString();
        }
        return null;
    }
}
