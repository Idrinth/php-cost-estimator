<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ParsesStaticTextFile::class)]
class ParsesStaticTextFileTest extends TestCase
{
    public static function provideMatchingAsts(): array
    {
        return [
            'parse_ini_file' => [
                new FuncCall(new Node\Name('parse_ini_file')),
            ],
            'simplexml_load_file' => [
                new FuncCall(new Node\Name('simplexml_load_file')),
            ],
            'DOMDocument::load' => [
                new Node\Expr\MethodCall(
                    new Node\Expr\Variable('dom', ['idrinth-type' => 'DOMDocument']),
                    'load',
                ),
            ],
        ];
    }
    #[Test]
    #[DataProvider('provideMatchingAsts')]
    public function astNodeIsFileSystemRead(Node $astNode): void
    {
        $sut = new ParsesStaticTextFile();
        self::assertTrue($sut->applies($astNode));
    }
}
