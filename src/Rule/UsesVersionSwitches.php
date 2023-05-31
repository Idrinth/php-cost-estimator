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
        if ($astNode instanceof Node\Expr\FuncCall && $astNode->name instanceof Node\Name) {
            return $astNode->name->toLowerString() === 'phpversion';
        }
        if ($astNode instanceof Node\Expr\ConstFetch && $astNode->name instanceof Node\Name) {
            return $astNode->name->toString() === 'PHP_VERSION';
        }
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
