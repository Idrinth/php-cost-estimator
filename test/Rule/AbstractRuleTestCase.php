<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

abstract class AbstractRuleTestCase extends TestCase
{
    protected abstract function getRule(): Rule;
    protected abstract function getExpectedCost(): Cost;
    public static abstract function provideMatchingASTs(): array;
    protected abstract function getExpectedGroup(): RuleSet;
    #[Test]
    #[DataProvider('provideMatchingASTs')]
    public function astNodeIsFileSystemRead(Node $astNode): void
    {
        self::assertTrue($this->getRule()->applies($astNode));
    }
    #[Test]
    public function isOfExpectedCost(): void
    {
        self::assertSame($this->getExpectedCost(), $this->getRule()->cost());
    }
    #[Test]
    public function hasReasoning(): void
    {
        $this->assertNotEmpty($this->getRule()->reasoning());
    }
    #[Test]
    public function hasExpectedGroup(): void
    {
        $this->assertSame($this->getExpectedGroup(), $this->getRule()->set());
    }
    #[Test]
    public function isRelevantToAtLeastOneEnvironment(): void
    {
        $rule = $this->getRule();
        $this->assertTrue($rule->relevant(PHPEnvironment::BOTH));
        $this->assertTrue($rule->relevant(PHPEnvironment::CLI) || $rule->relevant(PHPEnvironment::WEB));
    }
}