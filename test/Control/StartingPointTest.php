<?php

namespace De\Idrinth\PhpCostEstimator\Control;

use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(StartingPoint::class)]
class StartingPointTest extends TestCase
{
    #[Test]
    public function providesValuesUsedInConstruction(): void
    {
        $sut = new StartingPoint(PHPEnvironment::SERVER, 90);
        self::assertSame(90, $sut->callFactor);
        self::assertSame(PHPEnvironment::SERVER, $sut->phpEnvironment);
    }
}
