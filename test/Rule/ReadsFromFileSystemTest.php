<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReadsFromFileSystem::class)]
final class ReadsFromFileSystemTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
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
    protected function getExpectedCost(): Cost
    {
        return Cost::MEDIUM_LOW;
    }
    protected function getRule(): Rule
    {
        return new ReadsFromFileSystem();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }
}
