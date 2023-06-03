<?php

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Configuration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionLikeCallCount::class)]
class FunctionLikeCallCountTest extends TestCase
{
    #[Test]
    public function canBeConstructed(): void
    {
        $sut = new FunctionLikeCallCount(
            $func = new FunctionLike(
                'test',
                new InheritanceList(),
                new CallableList($this->createStub(Configuration::class), new InheritanceList())
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
