<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesArrayKeyExists::class)]
class UsesArrayKeyExistsTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'array_key_exists' => [
                new FuncCall(new Node\Name('array_key_exists')),
            ],
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsRemoteCall(Node $astNode): void
    {
        $sut = new UsesArrayKeyExists();
        self::assertTrue($sut->applies($astNode));
    }
}
