<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use PhpParser\Node;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(QueriesDatabase::class)]
final class QueriesDatabaseTest extends TestCase
{
    public static function provideMatchingAsts(): array
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
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsStaticComparison(Node $astNode): void
    {
        $sut = new QueriesDatabase();
        self::assertTrue($sut->applies($astNode));
    }
    #[Test]
    public function isOfExpectedCost(): void
    {
        $sut = new QueriesDatabase();
        self::assertSame(Cost::VERY_LOW, $sut->cost());
    }
    #[Test]
    public function hasReasoning(): void
    {
        $sut = new QueriesDatabase();
        $this->assertNotEmpty($sut->reasoning());
    }
}
