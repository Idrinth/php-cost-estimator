<?php

namespace De\Idrinth\PhpCostEstimator\Control;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AssumedSize::class)]
final class AssumedSizeTest extends TestCase
{
    #[Test]
    public function providesValuesUsedInConstruction(): void
    {
        $sut = new AssumedSize(245);
        self::assertSame(245, $sut->elements);
    }
}
