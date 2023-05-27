<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class CallStackBuilder extends NodeVisitorAbstract
{
    private string $context = '';
    public function __construct(private readonly CallableList $callableList)
    {
    }

    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Expr\FuncCall) {
            $this->callableList->registerCallee($this->context, $node->name->toString(), 1);
            return null;
        }
        if ($node instanceof Node\FunctionLike && $node->name instanceof Node\Identifier) {
            $this->context = $node->name->toString();
        }
        return null;
    }
}
