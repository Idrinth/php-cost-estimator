<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class WritesToFileSystem implements Rule
{
    public function reasoning(): string
    {
        return 'Writing to the file system is always relatively slow and it scales badly.';
    }

    public function cost(): Cost
    {
        return Cost::MEDIUM_HIGH;
    }

    public function applies(Node $astNode): bool
    {
        if (!($astNode instanceof Node\Expr\FuncCall)) {
            return false;
        }
        $name = $astNode->name;
        if (!($name instanceof Node\Name)) {
            return false;
        }
        $name = $name->hasAttribute('namespacedName') && !$astNode->hasAttribute('idrinth-fallback') ? $name->getAttribute('namespacedName')->toString() : $name->toString();
        if ($name === 'file_put_contents') {
            return true;
        }
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return PHPEnvironment::CLI !== $phpEnvironment;
    }
}
