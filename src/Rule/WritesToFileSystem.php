<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class WritesToFileSystem implements \De\Idrinth\PhpCostEstimator\Rule
{
    public function reasoning(): string
    {
        return 'Writing to the file system is always relatively slow and it scales badly.';
    }

    public function cost(): Cost
    {
        return Cost::MEDIUM_HIGH;
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