<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesRemoteCall::class)]
final class UsesRemoteCallTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'curl_exec' => [
                new FuncCall(new Node\Name('curl_exec')),
            ],
            'curl_multi_exec' => [
                new FuncCall(new Node\Name('curl_multi_exec')),
            ]
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsRemoteCall(Node $astNode): void
    {
        $sut = new UsesRemoteCall();
        self::assertTrue($sut->applies($astNode));
    }
}
