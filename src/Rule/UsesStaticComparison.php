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
