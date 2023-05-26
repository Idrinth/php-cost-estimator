<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class UsesInArrayOnLargeArray implements \De\Idrinth\PhpCostEstimator\Rule
{
    public function reasoning(): string
    {
        return 'in_array is slow on large arrays, so it should be avoided if possible.';
    }

    public function cost(): Cost
    {
        return Cost::VERY_HIGH;
    }

    public function applies(Node $astNode): bool
    {
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::DESIGN_FLAW;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return true;
    }
}
