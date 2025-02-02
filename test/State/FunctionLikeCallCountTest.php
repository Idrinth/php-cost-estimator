<?php

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Configuration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionLikeCallCount::class)]
class FunctionLikeCallCountTest extends TestCase
{
    #[Test]
    public function canBeConstructed(): void
    {
        $this->markTestIncomplete();
        $sut = new FunctionLikeCallCount(
            $func = $this->createStub(FunctionLike::class),
            111,
        );
        self::assertSame(111, $sut->count);
        self::assertSame($func, $sut->callee);
    }
}
