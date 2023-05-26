<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class UnnecessaryTypeDeclarations implements Rule
{
    public function reasoning(): string
    {
        return 'Any type declaration adds a check during runtime, so they slow down the requests slightly.';
    }

    public function cost(): Cost
    {
        return Cost::VERY_LOW;
    }

    public function applies(Node $astNode, PHPEnvironment $phpEnvironment): bool
    {
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::CONTROVERSIAL;
    }
}