<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class UsesArrayKeyExists implements Rule
{
    public function reasoning(): string
    {
        return 'array_key_exists is slower than isset or ?? and is unnecessary in most cases.';
    }

    public function cost(): Cost
    {
        return Cost::LOW;
    }

    public function applies(Node $astNode): bool
    {
        if (!($astNode instanceof Node\Expr\FuncCall)) {
            return false;
        }
        if (!($astNode->name instanceof Node\Name)) {
            return false;
        }
        if ($astNode->name->toString() === 'array_key_exists') {
            return true;
        }
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return true;
    }
}
