<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

class UsesRemoteCall implements Rule
{
    public function reasoning(): string
    {
        return 'Any remote call is expensive, since there is network time involved.';
    }

    public function cost(): int
    {
        return Cost::MEDIUM_HIGH;
    }

    public function applies(Node $astNode, string $phpEnvironment): bool
    {
        return true;
    }

    public function set(): string
    {
        return RuleSet::BEST_PRACTICES;
    }
}