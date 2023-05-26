<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

class UsesVersionSwitches implements \De\Idrinth\PhpCostEstimator\Rule
{
    public function reasoning(): string
    {
        return 'The version of an application\'s php is not going to suddenly change while deployed.';
    }

    public function cost(): int
    {
        return Cost::LOW;
    }

    public function applies(Node $astNode, string $phpEnvironment): bool
    {
        return false;
    }

    public function set(): string
    {
        return RuleSet::CONTROVERSIAL;
    }
}
