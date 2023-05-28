<?php

namespace De\Idrinth\PhpCostEstimator\Control;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(CostModify::class)]
class CostModifyTest extends TestCase
{
    #[Test]
    public function providesValuesUsedInConstruction(): void
    {
        $sut = new CostModify('Example\\Class', 245);
        self::assertSame(245, $sut->cost);
        self::assertSame('Example\\Class', $sut->ruleClassName);
    }
}
