<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WritesToFileSystem::class)]
final class WritesToFileSystemTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new WritesToFileSystem();
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::MEDIUM_HIGH;
    }
    public static function provideMatchingASTs(): array
    {
        return [
            'file_put_contents' => [
                new FuncCall(new Name('file_put_contents')),
            ],
        ];
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }
}
