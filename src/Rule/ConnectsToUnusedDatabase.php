<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class ConnectsToUnusedDatabase implements Rule
{

    public function reasoning(): string
    {
        return 'Any connection to another system is cost intensive.';
    }

    public function cost(): Cost
    {
        return Cost::MEDIUM;
    }

    public function applies(Node $astNode, PHPEnvironment $phpEnvironment): bool
    {
        return true;
    }

    public function set(): RuleSet
    {
        return RuleSet::CONTROVERSIAL;
    }
}