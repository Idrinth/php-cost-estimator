<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class LocksFileSystem implements Rule
{
    public function reasoning(): string
    {
        return 'Applying a lock on the file system prevents systems from scaling.';
    }

    public function cost(): Cost
    {
        return Cost::HIGH;
    }

    public function applies(Node $astNode, PHPEnvironment $phpEnvironment): bool
    {
        if (PHPEnvironment::CLI === $phpEnvironment) {
            return false;
        }
        if (!($astNode instanceof Node\Expr\FuncCall))  {
            return false;
        }
        if (!($astNode->name instanceof Node\Name)) {
            return false;
        }
        if ($astNode->name->toLowerString() === 'flock') {
            return true;
        }
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::DESIGN_FLAW;
    }
}
