<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class UsesReflection implements Rule
{
    public function reasoning(): string
    {
        return 'Reflection on code that should be static can usually be replaced with a build process.';
    }

    public function cost(): Cost
    {
        return Cost::MEDIUM_LOW;
    }

    public function applies(Node $astNode): bool
    {
        if ($astNode instanceof Node\Expr\New_ && $astNode->class instanceof Node\Name) {
            return in_array($astNode->class->toString(), [
                'ReflectionClass',
                'ReflectionMethod',
                'ReflectionFunction',
                'ReflectionAttribute',
            ]);
        }
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BUILD_PROCESS_ISSUE;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return PHPEnvironment::CLI !== $phpEnvironment;
    }
}
