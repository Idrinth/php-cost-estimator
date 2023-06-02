<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use De\Idrinth\PhpCostEstimator\State\FunctionDefinitionList;
use De\Idrinth\PhpCostEstimator\State\InheritanceList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class FunctionListBuilder extends NodeVisitorAbstract
{
    private string $namespace = '';
    private string $class;

    public function __construct(
        private readonly FunctionDefinitionList $functions,
        private readonly CallableList $callables,
    ) {
    }

    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        }
        if ($node instanceof Node\Stmt\ClassLike && $node->name instanceof Node\Identifier) {
            $this->class = $node->name->toString();
        }
        if ($node instanceof Node\Stmt\Function_ && $node->name instanceof Node\Identifier) {
            $this->functions->add($this->namespace . '\\' . $node->name->toString());
        }
        if ($node instanceof Node\Stmt\ClassMethod && !$node->isAbstract() && $this->class !== '' && $node->name instanceof Node\Identifier) {
            $this->functions->add($this->namespace . '\\' . $this->class . '::' . $node->name->toString());
            $this->callables->markFound($this->namespace . '\\' . $this->class . '::' . $node->name->toString());
        }
        return null;
    }
}
