<?php

namespace De\Idrinth\PhpCostEstimator\Control;

use De\Idrinth\PhpCostEstimator\Cost;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(CostModify::class)]
final class CostModifyTest extends TestCase
{
    #[Test]
    public function providesValuesUsedInConstruction(): void
    {
        $sut = new CostModify('Example\\Class', Cost::HIGH);
        self::assertSame(Cost::HIGH, $sut->cost);
        self::assertSame('Example\\Class', $sut->ruleClassName);
    }
}
