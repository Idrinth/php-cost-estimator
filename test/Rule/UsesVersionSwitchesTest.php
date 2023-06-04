<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesVersionSwitches::class)]
class UsesVersionSwitchesTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'PHP_VERSION' => [
                new Node\Expr\ConstFetch(new Node\Name('PHP_VERSION')),
            ],
            'phpversion()' => [
                new FuncCall(new Node\Name('phpversion')),
            ],
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsVersionSwitch(Node $astNode): void
    {
        $sut = new UsesVersionSwitches();
        self::assertTrue($sut->applies($astNode));
    }
    #[Test]
    public function isOfExpectedCost(): void
    {
        $sut = new UsesVersionSwitches();
        self::assertSame(Cost::LOW, $sut->cost());
    }
    #[Test]
    public function hasReasoning(): void
    {
        $sut = new UsesVersionSwitches();
        $this->assertNotEmpty($sut->reasoning());
    }
}
