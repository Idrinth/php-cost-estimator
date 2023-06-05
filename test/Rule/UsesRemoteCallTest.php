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

#[CoversClass(UsesRemoteCall::class)]
final class UsesRemoteCallTest extends AbstractRuleTestCase
{
    public static function provideMatchingASTs(): array
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
    public function getExpectedCost(): Cost
    {
        return Cost::MEDIUM_HIGH;
    }
    public function getRule(): Rule
    {
        return new UsesRemoteCall();
    }
    public function getExpectedGroup(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }
}
