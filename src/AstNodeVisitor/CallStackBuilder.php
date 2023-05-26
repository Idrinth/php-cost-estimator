<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class CallStackBuilder extends NodeVisitorAbstract
{
    private string $context;
    private CallableList $callableList;

    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Expr\FuncCall) {
            $this->callableList->registerCallee($this->context, $node->name->toString());
            return null;
        }
        if ($node instanceof FunctionLike) {
            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
        return null;
    }
}
