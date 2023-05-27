<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\Control\AssumedLoops;
use De\Idrinth\PhpCostEstimator\Control\AssumedSize;
use De\Idrinth\PhpCostEstimator\Control\CostModify;
use De\Idrinth\PhpCostEstimator\Control\RuleIgnore;
use De\Idrinth\PhpCostEstimator\Control\StartingPoint;
use De\Idrinth\PhpCostEstimator\State\CallableList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class ConfigurationReader extends NodeVisitorAbstract
{
    public function __construct(private readonly CallableList $callableList)
    {
    }
    public function enterNode(Node $node): ?int
    {
        if (!($node->name instanceof Node\Identifier)) {
            return null;
        }
        if ($node->hasAttribute(AssumedLoops::class)) {
            //track;
        }
        if ($node->hasAttribute(AssumedSize::class)) {
            //track;
        }
        if ($node->hasAttribute(CostModify::class)) {
            //track;
        }
        if ($node->hasAttribute(RuleIgnore::class)) {
            //track;
        }
        if ($node->hasAttribute(StartingPoint::class)) {
            $this->callableList->markStart(
                $node->name->toString(),
                $node->getAttribute(StartingPoint::class)->environment
            );
        }
        return null;
    }
}
