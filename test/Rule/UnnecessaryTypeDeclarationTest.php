<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UnnecessaryTypeDeclaration::class)]
class UnnecessaryTypeDeclarationTest extends TestCase
{
    public static function provideMatchingAsts(): array
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
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsStaticComparison(Node $astNode): void
    {
        $sut = new UnnecessaryTypeDeclaration();
        self::assertTrue($sut->applies($astNode));
    }
    #[Test]
    public function isOfExpectedCost(): void
    {
        $sut = new UnnecessaryTypeDeclaration();
        self::assertSame(Cost::VERY_LOW, $sut->cost());
    }
    #[Test]
    public function hasReasoning(): void
    {
        $sut = new UnnecessaryTypeDeclaration();
        $this->assertNotEmpty($sut->reasoning());
    }
}
