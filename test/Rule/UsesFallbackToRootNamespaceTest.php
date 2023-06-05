<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UsesFallbackToRootNamespace::class)]
class UsesFallbackToRootNamespaceTest extends AbstractRuleTestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'array_key_exists' => [
                new FuncCall(new Node\Name('array_key_exists'), [], ['idrinth-fallback' => true]),
            ],
        ];
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::VERY_LOW;
    }
    protected function getRule(): Rule
    {
        return new UsesFallbackToRootNamespace();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }
}
