<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UsesInArrayOnLargeArray::class)]
class UsesInArrayOnLargeArrayTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new UsesInArrayOnLargeArray();
    }

    protected function getExpectedCost(): Cost
    {
        return Cost::VERY_HIGH;
    }

    public static function provideMatchingASTs(): array
    {
        return [
            'in_array(1, $a, true)' => [
                new FuncCall(new Name('in_array'), [
                    new Arg(new DNumber(1)),
                    new Arg(new Variable(new Name('a'), ['idrinth-size' => 101])),
                    new Arg(new ConstFetch(new Name('true'))),
                ]),
            ],
            'in_array(1, [...100...], true)' => [
                new FuncCall(new Name('in_array'), [
                    new Arg(new DNumber(1)),
                    new Arg(new Array_(array_fill(0, 101, new ArrayItem(new DNumber(1))))),
                    new Arg(new ConstFetch(new Name('true'))),
                ]),
            ],
        ];
    }

    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::DESIGN_FLAW;
    }
}
