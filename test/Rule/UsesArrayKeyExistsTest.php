<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UsesArrayKeyExists::class)]
class UsesArrayKeyExistsTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
    {
        return [
            'array_key_exists' => [
                new FuncCall(new Node\Name('array_key_exists')),
            ],
        ];
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::LOW;
    }
    protected function getRule(): Rule
    {
        return new UsesArrayKeyExists();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }
}
