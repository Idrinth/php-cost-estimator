<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PHPUnit\Event\Code\ClassMethod;

final class UnnecessaryTypeDeclaration implements Rule
{
    public function reasoning(): string
    {
        return 'Any type declaration adds a check during runtime, so they slow down the requests slightly.';
    }

    public function cost(): Cost
    {
        return Cost::VERY_LOW;
    }

    public function applies(Node $astNode): bool
    {
        if ($astNode instanceof Node\Stmt\ClassMethod) {
            if ($astNode->isPrivate()) {
                foreach ($astNode->params as $param) {
                    if ($param->type !== null) {
                        return true;
                    }
                }
            }
        }
        if ($astNode instanceof Node\Stmt\Property) {
            if ($astNode->isPrivate()) {
                if ($astNode->type !== null) {
                    return true;
                }
            }
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
