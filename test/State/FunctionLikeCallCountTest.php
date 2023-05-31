<?php

namespace De\Idrinth\PhpCostEstimator\State;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionLikeCallCount::class)]
class FunctionLikeCallCountTest extends TestCase
{
    #[Test]
    #[DependsOnClass(FunctionLike::class)]
    public function canBeConstructed(): void
    {
        $sut = new FunctionLikeCallCount(
            $func = new FunctionLike(
                'test',
            ),
            111,
        );
        self::assertInstanceOf(
            FunctionLikeCallCount::class,
            $sut
        );
        self::assertSame(111, $sut->count);
        self::assertSame($func, $sut->callee);
    }
}
