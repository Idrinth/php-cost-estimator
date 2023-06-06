<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesStaticComparison::class)]
final class UsesStaticComparisonTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
    {
        return [
            'PHP_VERSION' => [
                new Node\Expr\BinaryOp\Equal(
                    new Node\Expr\ConstFetch(new Node\Name('PHP_VERSION')),
                    new Node\Scalar\String_('7.4.0'),
                ),
            ],
            '1==2' => [
                new Node\Expr\BinaryOp\Equal(
                    new Node\Scalar\LNumber(1),
                    new Node\Scalar\LNumber(2),
                ),
            ],
            '1>2' => [
                new Node\Expr\BinaryOp\Greater(
                    new Node\Scalar\LNumber(1),
                    new Node\Scalar\LNumber(2),
                ),
            ],
            'in_array(1, [...100...], true)' => [
                new FuncCall(new Name('in_array'), [
                    new Arg(new DNumber(1)),
                    new Arg(new Array_(array_fill(0, 2, new ArrayItem(new DNumber(1))))),
                    new Arg(new ConstFetch(new Name('true'))),
                ]),
            ],
        ];
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::VERY_LOW;
    }
    protected function getRule(): Rule
    {
        return new UsesStaticComparison();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::BUILD_PROCESS_ISSUE;
    }
}
