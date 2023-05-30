<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class QueriesDatabase implements Rule
{
    public function reasoning(): string
    {
        return 'Any query to a database can potentially create performance issues if done too often.';
    }

    public function cost(): Cost
    {
        return Cost::VERY_LOW;
    }

    public function applies(Node $astNode): bool
    {
        if ($astNode instanceof Node\Expr\MethodCall) {
            $var = $astNode->var;
            if ($var instanceof Node\Expr\Variable) {
                if ($var->hasAttribute('idrinth-type') && $var->getAttribute('idrinth-type') === 'PDO') {
                    return in_array($astNode->name->toString(), ['query', 'exec', 'prepare'], true);
                }
            }
        }
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::DESIGN_FLAW;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return true;
    }
}
