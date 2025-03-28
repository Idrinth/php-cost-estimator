<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use De\Idrinth\PhpCostEstimator\State\FunctionDefinitionList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class FallbackToRootNamespaceChecker extends NodeVisitorAbstract
{
    private string $namespace = '';

    public function __construct(private readonly FunctionDefinitionList $functions)
    {
    }
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        }
        if ($node instanceof Node\Expr\FuncCall && $this->namespace !== '') {
            if ($node->name instanceof Node\Name && $node->name->hasAttribute('namespacedName')) {
                $name = $node->name->getAttribute('namespacedName');
                if (str_contains(substr($name->toString(), 1), '\\') && !$this->functions->has($name->toString()) && str_starts_with($name->toString(), $this->namespace)) {
                    $node->setAttribute('idrinth-fallback', true);
                }
            }
        }
        return $node;
    }
    public function afterTraverse(array $nodes): void
    {
        $this->namespace = '';
    }
}