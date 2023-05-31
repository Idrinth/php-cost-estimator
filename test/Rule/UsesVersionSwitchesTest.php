<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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
}
