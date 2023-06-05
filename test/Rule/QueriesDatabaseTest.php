<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(QueriesDatabase::class)]
final class QueriesDatabaseTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
    {
        return [
            'PDO::prepare' => [
                new Node\Expr\MethodCall(
                    new Node\Expr\Variable('test', [
                        'idrinth-type' => 'PDO',
                    ]),
                    'prepare',
                ),
            ],
            'PDO::exec' => [
                new Node\Expr\MethodCall(
                    new Node\Expr\Variable('test', [
                        'idrinth-type' => 'PDO',
                    ]),
                    'exec',
                ),
            ],
            'PDO::query' => [
                new Node\Expr\MethodCall(
                    new Node\Expr\Variable('test', [
                        'idrinth-type' => 'PDO',
                    ]),
                    'query',
                ),
            ],
        ];
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::VERY_LOW;
    }
    protected function getRule(): Rule
    {
        return new QueriesDatabase();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::DESIGN_FLAW;
    }
}
