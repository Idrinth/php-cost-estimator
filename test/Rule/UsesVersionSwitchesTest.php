<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UsesVersionSwitches::class)]
class UsesVersionSwitchesTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
    {
        return [
            'PHP_VERSION' => [
                new Node\Expr\ConstFetch(new Node\Name('PHP_VERSION')),
            ],
            'phpversion()' => [
                new FuncCall(new Node\Name('phpversion')),
            ],
        ];
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::LOW;
    }
    protected function getRule(): Rule
    {
        return new UsesVersionSwitches();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::CONTROVERSIAL;
    }
}
