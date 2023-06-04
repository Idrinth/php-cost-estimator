<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class UsesFallbackToRootNamespace implements Rule
{
    public function reasoning(): string
    {
        return 'For functions and constants there are two lookups instead of one when leaving the namespace out';
    }

    public function cost(): Cost
    {
        return Cost::VERY_LOW;
    }

    public function applies(Node $astNode): bool
    {
        return $astNode->hasAttribute('idrinth-fallback');
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
