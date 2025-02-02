<?php

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Configuration;
use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionLike::class)]
final class FunctionLikeTest extends TestCase
{
    #[Test]
    public function emptyFunctionHasNoCost(): void
    {
        $this->markTestSkipped();
        $functionLike = new FunctionLike('test', new InheritanceList(), new CallableList($this->createStub(Configuration::class), new InheritanceList()));
        self::assertSame(0, $functionLike->cost(PHPEnvironment::WEB));
    }
    #[Test]
    public function readingFromFileSystemHasACostOfFour(): void
    {
        $this->markTestSkipped();
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
        $functionLike = new FunctionLike('test', new InheritanceList(), new CallableList($this->createStub(Configuration::class), new InheritanceList()));
        $functionLike->registerRule($rule);
        self::assertSame(4, $functionLike->cost(PHPEnvironment::WEB));
    }
    #[Test]
    public function readingFromFileSystemInAChildHasACostOfFourTimesUsage(): void
    {
        $this->markTestSkipped();
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
        $innerFunctionLike = new FunctionLike('test', new InheritanceList(), new CallableList($this->createStub(Configuration::class), new InheritanceList()));
        $innerFunctionLike->registerRule($rule);
        $functionLike = new FunctionLike('test2', new InheritanceList(), new CallableList($this->createStub(Configuration::class), new InheritanceList()));
        $functionLike->registerCallee($innerFunctionLike, 3);
        self::assertSame(24, $functionLike->cost(PHPEnvironment::WEB));
    }
}
