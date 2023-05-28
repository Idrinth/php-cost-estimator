<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReadsFromFileSystem::class)]
final class ReadsFromFileSystemTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'file_get_contents' => [
                new FuncCall(new Node\Name('file_get_contents')),
            ],
            'scandir' => [
                new FuncCall(new Node\Name('scandir')),
            ],
            'is_file' => [
                new FuncCall(new Node\Name('is_file')),
            ],
            'is_dir' => [
                new FuncCall(new Node\Name('is_dir')),
            ],
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsFileSystemRead(Node $astNode): void
    {
        $sut = new ReadsFromFileSystem();
        self::assertTrue($sut->applies($astNode));
    }
}
