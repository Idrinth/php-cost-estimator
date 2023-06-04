<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesFallbackToRootNamespace::class)]
class UsesFallbackToRootNamespaceTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'array_key_exists' => [
                new FuncCall(new Node\Name('array_key_exists'), [], ['idrinth-fallback' => true]),
            ],
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsRemoteCall(Node $astNode): void
    {
        $sut = new UsesFallbackToRootNamespace();
        self::assertTrue($sut->applies($astNode));
    }
    #[Test]
    public function isOfExpectedCost(): void
    {
        $sut = new UsesFallbackToRootNamespace();
        self::assertSame(Cost::VERY_LOW, $sut->cost());
    }
    #[Test]
    public function hasReasoning(): void
    {
        $sut = new UsesFallbackToRootNamespace();
        $this->assertNotEmpty($sut->reasoning());
    }
}
