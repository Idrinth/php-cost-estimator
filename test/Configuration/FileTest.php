<?php

namespace De\Idrinth\PhpCostEstimator\Configuration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(File::class)]
class FileTest extends TestCase
{

    #[Test]
    public function readThisProjectsConfiguration(): void
    {
        $sut = new File(__DIR__ . '/../..');
        self::assertCount(16, iterator_to_array($sut->ruleWhitelist()));
        self::assertSame('8.2.6', $sut->phpVersion());
        self::assertSame(['src'], $sut->foldersToScan());
        self::assertSame(0, $sut->minSeverity());
        self::assertFalse($sut->checkCleanedDependencies());
        self::assertFalse($sut->checkOptimizedAutoloader());
    }
}
