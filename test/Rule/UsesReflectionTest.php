<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesReflection::class)]
class UsesReflectionTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'ReflectionClass' => [
                new Node\Expr\New_(new Node\Name('ReflectionClass')),
            ],
            'ReflectionMethod' => [
                new Node\Expr\New_(new Node\Name('ReflectionMethod')),
            ],
            'ReflectionFunction' => [
                new Node\Expr\New_(new Node\Name('ReflectionFunction')),
            ],
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsReflection(Node $astNode): void
    {
        $sut = new UsesReflection();
        self::assertTrue($sut->applies($astNode));
    }
    #[Test]
    public function isOfExpectedCost(): void
    {
        $sut = new UsesReflection();
        self::assertSame(Cost::MEDIUM_LOW, $sut->cost());
    }
    #[Test]
    public function hasReasoning(): void
    {
        $sut = new UsesReflection();
        $this->assertNotEmpty($sut->reasoning());
    }
}
