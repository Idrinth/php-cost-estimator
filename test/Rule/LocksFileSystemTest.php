<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocksFileSystem::class)]
final class LocksFileSystemTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
    {
        return [
            'flock' => [
                new FuncCall(new Node\Name('flock')),
            ],
        ];
    }
    #[Test]
    public function getExpectedCost(): Cost
    {
        return Cost::HIGH;
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::DESIGN_FLAW;
    }

    protected function getRule(): Rule
    {
        return new LocksFileSystem();
    }
}
