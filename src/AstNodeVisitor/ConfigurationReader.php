<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\Control\AssumedLoops;
use De\Idrinth\PhpCostEstimator\Control\AssumedSize;
use De\Idrinth\PhpCostEstimator\Control\CostModify;
use De\Idrinth\PhpCostEstimator\Control\RuleIgnore;
use De\Idrinth\PhpCostEstimator\Control\StartingPoint;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\State\CallableList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class ConfigurationReader extends NodeVisitorAbstract
{
    private string $class = '';
    private string $namespace = '';
    private string $method = '';

    public function __construct(private readonly CallableList $callableList)
    {
    }
    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        }
        if ($node instanceof Node\Stmt\ClassLike) {
            $this->class = $node->name->toString();
        }
        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->method = $node->name->toString();
        }
        if ($node instanceof Node\Attribute && $node->name->toString() === 'StartingPoint') {
            $this->callableList->markStart(
                $this->namespace . '\\' . $this->class . '::' . $this->method,
                $node->args[0]->value->name->toString() === 'CLI' ? PHPEnvironment::CLI : PHPEnvironment::WEB,
            );
        }
        return null;
    }
    public function afterTraverse(array $nodes): null
    {
        $this->class = '';
        $this->namespace = '';
        $this->method = '';
        return null;
    }
}
