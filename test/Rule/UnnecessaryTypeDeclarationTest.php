<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UnnecessaryTypeDeclaration::class)]
class UnnecessaryTypeDeclarationTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
    {
        return [
            'private function func(Var $a)' => [
                new ClassMethod(
                    'func',
                    [
                        'flags' => Node\Stmt\Class_::MODIFIER_PRIVATE,
                        'params' => [
                            new Node\Param(new Node\Expr\Variable('a'), null, 'Var'),
                        ]
                    ],
                    [],
                ),
            ],
            'private function func($b, Var $a)' => [
                new ClassMethod(
                    'func',
                    [
                        'flags' => Node\Stmt\Class_::MODIFIER_PRIVATE,
                        'params' => [
                            new Node\Param(new Node\Expr\Variable('b')),
                            new Node\Param(new Node\Expr\Variable('a'), null, 'Var'),
                        ]
                    ],
                    [],
                ),
            ],
            [
                new Node\Stmt\Property(
                    Node\Stmt\Class_::MODIFIER_PRIVATE,
                    [new Node\Stmt\PropertyProperty('name', null)],
                    [],
                    'Type'
                )
            ]
        ];
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::VERY_LOW;
    }
    protected function getRule(): Rule
    {
        return new UnnecessaryTypeDeclaration();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::CONTROVERSIAL;
    }
}
