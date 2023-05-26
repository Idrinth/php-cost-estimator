<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class UsesReflection implements \De\Idrinth\PhpCostEstimator\Rule
{
    public function reasoning(): string
    {
        return 'Reflection on code that should be static can usually be replaced with a build process.';
    }

    public function cost(): Cost
    {
        return Cost::MEDIUM_LOW;
    }

    public function applies(Node $astNode, string $phpEnvironment): bool
    {
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BUILD_PROCESS_ISSUE;
    }
}
