<?php

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\PHPEnvironment;
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
        return [
            'empty function has no cost' => [0, new FunctionLike('test'), PHPEnvironment::BOTH],
        ];
    }
    #[DataProvider('provideCalculations')]
    #[Test]
    public function calculationsOfCostMatch(
        int $expectation,
        FunctionLike $functionLike,
        PHPEnvironment $environment
    ): void {
        $this->assertSame($expectation, $functionLike->cost($environment));
    }
}
