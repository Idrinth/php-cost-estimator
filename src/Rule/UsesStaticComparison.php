<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

class UsesStaticComparison implements Rule
{
    public function reasoning(): string
    {
        return 'A comparison that is always true or always false can be simplified.';
    }

    public function cost(): int
    {
        return Cost::VERY_LOW;
    }

    public function applies(Node $astNode, string $phpEnvironment): bool
    {
        return false;
    }

    public function set(): string
    {
        return RuleSet::BUILD_PROCESS_ISSUE;
    }
}
