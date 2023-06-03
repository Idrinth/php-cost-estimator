<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class CallStackBuilder extends NodeVisitorAbstract
{
    private string $class = '';
    private string $context = '';

    public function __construct(private readonly CallableList $callableList)
    {
    }

    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\ClassLike && $node->name instanceof Node\Identifier) {
            $this->class = $node->namespacedName->toString();
        }
        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->context = $this->class . '::' . $node->name->toString();
        }
        if ($node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Name) {
            $this->callableList->registerCallee($this->context, $node->name->toString(), 1);
        }
        if ($node instanceof Node\Expr\MethodCall && $node->var->hasAttribute('idrinth-type') && $node->name instanceof Node\Identifier) {
            $this->callableList->registerCallee($this->context, $node->var->getAttribute('idrinth-type') . '::' . $node->name->toString(), 1);
        }
        if ($node instanceof Node\Expr\StaticCall && $node->class instanceof Node\Name && $node->name instanceof Node\Identifier) {
            $this->callableList->registerCallee($this->context, $node->class->toString() . '::' . $node->name->toString(), 1);
        }
        if ($node instanceof Node\Expr\StaticCall && $node->class instanceof Node\Expr\Variable && $node->name instanceof Node\Identifier) {
            $this->callableList->registerCallee($this->context, $node->class->getAttribute('idrinth-type') . '::' . $node->name->toString(), 1);
        }
        if ($node instanceof Node\Expr\New_ && $node->class instanceof Node\Name) {
            $this->callableList->registerCallee($this->context, $node->class->toString() . '::__construct', 1);
        }
        return null;
    }
    public function beforeTraverse(array $nodes): null
    {
        $this->context = '';
        $this->class = '';
        return null;
    }
}
