<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class BuildsUnusedObject implements \De\Idrinth\PhpCostEstimator\Rule
{
    public function reasoning(): string
    {
        return 'Unused objects still take up processing time and memory.';
    }

    public function cost(): Cost
    {
        return Cost::LOW;
    }

    public function applies(Node $astNode, PHPEnvironment $phpEnvironment): bool
    {
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }
}