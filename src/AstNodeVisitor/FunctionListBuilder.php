<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use De\Idrinth\PhpCostEstimator\State\FunctionDefinitionList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class FunctionListBuilder extends NodeVisitorAbstract
{
    private string $namespace = '';

    public function __construct(private readonly FunctionDefinitionList $functions)
    {
    }

    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        }
        if ($node instanceof Node\Stmt\Function_ && $node->name instanceof Node\Identifier) {
            $this->functions->add($this->namespace . '\\' . $node->name->toString());
        }
        return null;
    }
}
