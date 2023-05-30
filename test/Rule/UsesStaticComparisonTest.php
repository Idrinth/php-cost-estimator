<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesStaticComparison::class)]
final class UsesStaticComparisonTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'phpversion()' => [
                new Node\Expr\BinaryOp\Equal(
                    new FuncCall(new Node\Name('phpversion')),
                    new Node\Scalar\String_('7.4.0'),
                ),
            ],
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
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsStaticComparison(Node $astNode): void
    {
        $sut = new UsesStaticComparison();
        self::assertTrue($sut->applies($astNode));
    }
}
