<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\Control\AssumedLoops;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class DeLooper extends NodeVisitorAbstract
{
    private int $loops = 5;
    public function leaveNode(Node $node): ?array
    {
        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->loops = 5;
        }
        if ($node instanceof Node\Attribute && $node->name->toString() === AssumedLoops::class) {
            $value = $node->args[0]->value;
            if ($value instanceof Node\Scalar\LNumber) {
                $this->loops = $value->value;
            }
        }
        if (
            $node instanceof Node\Stmt\Foreach_ ||
            $node instanceof Node\Stmt\For_ ||
            $node instanceof Node\Stmt\While_ ||
            $node instanceof Node\Stmt\Do_
        ) {
            $nodes = [];
            for ($i = 0; $i < $this->loops; $i++) {
                foreach ($node->stmts as $stmt) {
                    $nodes[] = $stmt;
                }
            }
            return $nodes;
        }
        return null;
    }
}