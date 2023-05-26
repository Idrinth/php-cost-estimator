<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

class UsesVersionSwitches implements Rule
{
    public function reasoning(): string
    {
        return 'The version of an application\'s php is not going to suddenly change while deployed.';
    }

    public function cost(): Cost
    {
        return Cost::LOW;
    }

    public function applies(Node $astNode): bool
    {
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::CONTROVERSIAL;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return true;
    }
}
