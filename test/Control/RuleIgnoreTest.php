<?php

namespace De\Idrinth\PhpCostEstimator\Control;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RuleIgnore::class)]
final class RuleIgnoreTest extends TestCase
{
    #[Test]
    public function providesValuesUsedInConstruction(): void
    {
        $sut = new RuleIgnore('Example\\Class');
        self::assertSame('Example\\Class', $sut->ruleClassName);
    }
}
