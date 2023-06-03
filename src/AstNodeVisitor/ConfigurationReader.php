<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\Control\AssumedLoops;
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
        if ($node instanceof Node\Attribute && $node->name->toString() === StartingPoint::class) {
            $recursionDepth = 5;
            $callFactor = 1;
            $phpEnvironment = PHPEnvironment::BOTH;
            foreach ($node->args as $pos => $arg) {
                if (($arg->name && $arg->name->toString() === 'recursionDepth') || ($pos === 2 && $arg->name === null)) {
                    $recursionDepth = $arg->value->value;
                }
                if (($arg->name && $arg->name->toString() === 'callFactor') || ($pos === 1 && $arg->name === null)) {
                    $callFactor = $arg->value->value;
                }
                if (($arg->name && $arg->name->toString() === 'phpEnvironment') || ($pos === 0 && $arg->name === null)) {
                    $expr = $arg->value;
                    if ($expr instanceof Node\Expr\ClassConstFetch) {
                        $phpEnvironment = match ($expr->name->toString()) {
                            'CLI' => PHPEnvironment::CLI,
                            'WEB' => PHPEnvironment::WEB,
                            'BOTH' => PHPEnvironment::BOTH,
                        };
                    }
                }
            }
            $this->callableList->markStart(
                $this->namespace . '\\' . $this->class . '::' . $this->method,
                $phpEnvironment,
                $callFactor,
                $recursionDepth,
            );
        }
        if ($node instanceof Node\Attribute && $node->name->toString() === RuleIgnore::class) {
            $this->callableList->markIgnored(
                $this->namespace . '\\' . $this->class . '::' . $this->method,
                $node->args[0]->value->name->toString(),
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
