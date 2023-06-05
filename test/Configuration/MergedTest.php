<?php

namespace De\Idrinth\PhpCostEstimator\Configuration;

use De\Idrinth\PhpCostEstimator\Configuration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Merged::class)]
class MergedTest extends TestCase
{
    #[Test]
    public function constructionReadsOutValuesGivenConfiguration(): void
    {
        $config = $this->createMock(Configuration::class);
        $config
            ->expects(self::once())
            ->method('minSeverity')
            ->willReturn(123344);
        $config
            ->expects(self::once())
            ->method('checkOptimizedAutoloader')
            ->willReturn(true);
        $config
            ->expects(self::once())
            ->method('checkCleanedDependencies')
            ->willReturn(true);
        $configuration = new Merged($config);
        self::assertSame(123344, $configuration->minSeverity());
        self::assertTrue($configuration->checkCleanedDependencies());
        self::assertTrue($configuration->checkOptimizedAutoloader());
        self::assertCount(0, iterator_to_array($configuration->ruleWhitelist()));
        self::assertSame([], iterator_to_array($configuration->foldersToScan()));
    }
    #[Test]
    public function constructionReadsOutDefaultValuesGivenNoConfiguration(): void
    {
        $configuration = new Merged();
        self::assertSame(0, $configuration->minSeverity());
        self::assertFalse($configuration->checkCleanedDependencies());
        self::assertFalse($configuration->checkOptimizedAutoloader());
        self::assertCount(0, iterator_to_array($configuration->ruleWhitelist()));
        self::assertSame([], iterator_to_array($configuration->foldersToScan()));
    }
}
