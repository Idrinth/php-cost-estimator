<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

class UsesRemoteCall implements Rule
{
    public function reasoning(): string
    {
        return 'Any remote call is expensive, since there is network time involved.';
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
        if ($astNode->name->toString() === 'curl_exec') {
            return true;
        }
        if ($astNode->name->toString() === 'curl_multi_exec') {
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
