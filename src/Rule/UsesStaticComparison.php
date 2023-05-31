<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

class UsesStaticComparison implements Rule
{
    public function reasoning(): string
    {
        return 'A comparison that is always true or always false can be simplified.';
    }

    public function cost(): Cost
    {
        return Cost::VERY_LOW;
    }

    public function applies(Node $astNode): bool
    {
        if (!($astNode instanceof Node\Expr\BinaryOp)) {
            return false;
        }
        if (!(
            $astNode instanceof Node\Expr\BinaryOp\Equal ||
            $astNode instanceof Node\Expr\BinaryOp\NotEqual ||
            $astNode instanceof Node\Expr\BinaryOp\Identical ||
            $astNode instanceof Node\Expr\BinaryOp\NotIdentical ||
            $astNode instanceof Node\Expr\BinaryOp\Greater ||
            $astNode instanceof Node\Expr\BinaryOp\GreaterOrEqual ||
            $astNode instanceof Node\Expr\BinaryOp\Smaller ||
            $astNode instanceof Node\Expr\BinaryOp\SmallerOrEqual ||
            $astNode instanceof Node\Expr\BinaryOp\Spaceship
        )) {
            return false;
        }
        if ($astNode->left instanceof Node\Scalar && $astNode->right instanceof Node\Scalar) {
            return true;
        }
        if ($astNode->left instanceof Node\Expr\ConstFetch && $astNode->right instanceof Node\Scalar) {
            return true;
        }
        if ($astNode->left instanceof Node\Scalar && $astNode->right instanceof Node\Expr\ConstFetch) {
            return true;
        }
        if ($astNode->left instanceof Node\Expr\ConstFetch && $astNode->right instanceof Node\Expr\ConstFetch) {
            return true;
        }
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BUILD_PROCESS_ISSUE;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return true;
    }
}
