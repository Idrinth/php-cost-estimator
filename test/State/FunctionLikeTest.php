<?php

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\Rule\ReadsFromFileSystem;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\Attributes\RequiresMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionLike::class)]
final class FunctionLikeTest extends TestCase
{
    #[Test]
    public function emptyFunctionHasNoCost(): void {
        $functionLike = new FunctionLike('test');
        self::assertSame(0, $functionLike->cost(PHPEnvironment::WEB));
    }
    #[Test]
    public function readingFromFileSystemHasACostOfFour(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule
            ->expects(self::once())
            ->method('cost')
            ->willReturn(Cost::MEDIUM_LOW);
        $rule
            ->expects(self::once())
            ->method('relevant')
            ->with(PHPEnvironment::WEB)
            ->willReturn(true);
        $functionLike = new FunctionLike('test');
        $functionLike->registerRule($rule);
        self::assertSame(4, $functionLike->cost(PHPEnvironment::WEB));
    }
    #[Test]
    #[DependsOnClass(FunctionLikeCallCount::class)]
    public function readingFromFileSystemInAChildHasACostOfFourTimesUsage(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule
            ->expects(self::once())
            ->method('cost')
            ->willReturn(Cost::MEDIUM);
        $rule
            ->expects(self::once())
            ->method('relevant')
            ->with(PHPEnvironment::WEB)
            ->willReturn(true);
        $innerFunctionLike = new FunctionLike('test');
        $innerFunctionLike->registerRule($rule);
        $functionLike = new FunctionLike('test2');
        $functionLike->registerCallee($innerFunctionLike, 3);
        self::assertSame(24, $functionLike->cost(PHPEnvironment::WEB));
    }
}
