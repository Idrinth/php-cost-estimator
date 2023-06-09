<?php

namespace De\Idrinth\PhpCostEstimator\Control;

use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(StartingPoint::class)]
final class StartingPointTest extends TestCase
{
    #[Test]
    public function providesValuesUsedInConstruction(): void
    {
        $sut = new StartingPoint(PHPEnvironment::WEB, 90);
        self::assertSame(90, $sut->callFactor);
        self::assertSame(PHPEnvironment::WEB, $sut->phpEnvironment);
    }
}
