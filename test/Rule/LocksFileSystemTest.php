<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocksFileSystem::class)]
final class LocksFileSystemTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'flock' => [
                new FuncCall(new Node\Name('flock')),
            ],
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsFileSystemRead(Node $astNode): void
    {
        $sut = new LocksFileSystem();
        self::assertTrue($sut->applies($astNode));
    }
}
