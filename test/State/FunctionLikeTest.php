<?php

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule\ReadsFromFileSystem;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionLike::class)]
final class FunctionLikeTest extends TestCase
{
    /**
     * @return array<string, array<int, int|FunctionLike|PHPEnvironment>>
     */
    public static function provideCalculations(): array
    {
        $fl1 = new FunctionLike('test');
        $fl1->registerRule(new ReadsFromFileSystem());
        $fl2 = new FunctionLike('test1');
        $fl2->registerCallee($fl1, 3);
        return [
            'empty function has no cost' => [0, new FunctionLike('test'), PHPEnvironment::BOTH],
            'reading from file system has a cost of 4' => [4, $fl1, PHPEnvironment::WEB],
            'reading from file system in child has a cost of 3x4' => [12, $fl2, PHPEnvironment::WEB],
        ];
    }
    #[DataProvider('provideCalculations')]
    #[Test]
    public function calculationsOfCostMatch(
        int $expectation,
        FunctionLike $functionLike,
        PHPEnvironment $environment
    ): void {
        self::assertSame($expectation, $functionLike->cost($environment));
    }
}
