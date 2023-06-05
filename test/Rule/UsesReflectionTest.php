<?php

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UsesReflection::class)]
class UsesReflectionTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
    {
        return [
            'ReflectionClass' => [
                new Node\Expr\New_(new Node\Name('ReflectionClass')),
            ],
            'ReflectionMethod' => [
                new Node\Expr\New_(new Node\Name('ReflectionMethod')),
            ],
            'ReflectionFunction' => [
                new Node\Expr\New_(new Node\Name('ReflectionFunction')),
            ],
        ];
    }
    protected function getExpectedCost(): Cost
    {
        return Cost::MEDIUM_LOW;
    }
    protected function getRule(): Rule
    {
        return new UsesReflection();
    }
    protected function getExpectedGroup(): RuleSet
    {
        return RuleSet::BUILD_PROCESS_ISSUE;
    }
}
