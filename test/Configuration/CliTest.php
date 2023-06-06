<?php

namespace De\Idrinth\PhpCostEstimator\Configuration;

use De\Idrinth\PhpCostEstimator\Rule\UsesReflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;

#[CoversClass(Cli::class)]
class CliTest extends TestCase
{
    #[Test]
    public function constructionReadsOutValuesFromInput(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input->expects($this->exactly(2))
            ->method('getOption')
            ->withAnyParameters()
            ->willReturnOnConsecutiveCalls(['1'], ['UsesReflection']);
        $input->expects($this->exactly(2))
            ->method('hasOption')
            ->withAnyParameters()
            ->willReturn(true);
        $configuration = new Cli($input);
        self::assertSame(1, $configuration->minSeverity());
        self::assertTrue($configuration->checkCleanedDependencies());
        self::assertTrue($configuration->checkOptimizedAutoloader());
        self::assertSame(UsesReflection::class, get_class(iterator_to_array($configuration->ruleWhitelist())[0]));
        self::assertSame([], iterator_to_array($configuration->foldersToScan()));
    }
}
