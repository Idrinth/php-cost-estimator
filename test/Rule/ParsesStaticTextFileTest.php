<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\RuleSet;
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
    #[Test]
    public function isOfExpectedCost(): void
    {
        $sut = new ParsesStaticTextFile();
        self::assertSame(Cost::MEDIUM_HIGH, $sut->cost());
    }
    #[Test]
    public function hasReasoning(): void
    {
        $sut = new ParsesStaticTextFile();
        $this->assertNotEmpty($sut->reasoning());
    }
    #[Test]
    public function hasExpectedGroup(): void
    {
        $sut = new ParsesStaticTextFile();
        $this->assertSame(RuleSet::BUILD_PROCESS_ISSUE, $sut->set());
    }
}
