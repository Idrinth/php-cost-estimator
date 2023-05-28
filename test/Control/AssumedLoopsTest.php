<?php

namespace De\Idrinth\PhpCostEstimator\Control;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AssumedLoops::class)]
final class AssumedLoopsTest extends TestCase
{
    #[Test]
    public function providesValuesUsedInConstruction(): void
    {
        $sut = new AssumedLoops(199, 'test');
        self::assertSame(199, $sut->factor);
        self::assertSame('test', $sut->variableName);
    }
}
