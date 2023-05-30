<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;

final class FunctionLike
{
    /**
     * @var Rule[]
     */
    private array $matchedRules = [];
    private bool $isStart = false;
    /**
     * @var FunctionLikeCallCount[]
     */
    private array $children = [];
    private PHPEnvironment $environment;
    /**
     * @var string[]
     */
    private array $ignoredRules = [];

    public function __construct(
        private readonly string $name,
    ) {
    }
    public function name(): string
    {
        return $this->name;
    }
    public function cost(PHPEnvironment $environment, int $callFactor = 1): int
    {
        $cost = 0;
        foreach ($this->matchedRules as $rule) {
            if ($rule->relevant($environment)) {
                match ($rule->cost()) {
                    Cost::VERY_LOW => $cost += 1 * $callFactor,
                    Cost::LOW => $cost += 2 * $callFactor,
                    Cost::MEDIUM_LOW => $cost += 4 * $callFactor,
                    Cost::MEDIUM => $cost += 8 * $callFactor,
                    Cost::MEDIUM_HIGH => $cost += 16 * $callFactor,
                    Cost::HIGH => $cost += 32 * $callFactor,
                    Cost::VERY_HIGH => $cost += 64 * $callFactor,
                };
            }
        }
        foreach ($this->children as $child) {
            $cost += $child->callee->cost($environment, $child->count * $callFactor);
        }
        return $cost;
    }
    public function matchedRules(): array
    {
        return $this->matchedRules;
    }
    public function isRoot(): bool
    {
        return $this->isStart;
    }
    public function environment(): PHPEnvironment
    {
        return $this->environment;
    }
    public function markStart(PHPEnvironment $environment): void
    {
        $this->isStart = true;
        $this->environment = $environment;
    }
    public function registerCallee(FunctionLike $callee, int $count = 1): void
    {
        foreach ($this->children as $pos => $child) {
            if ($child->callee === $callee) {
                $this->children[$pos] = new FunctionLikeCallCount($callee, $child->count + $count);
                return;
            }
        }
        $this->children[] = new FunctionLikeCallCount($callee, $count);
    }
    public function registerRule(Rule $rule): void
    {
        if (in_array(get_class($rule), $this->ignoredRules)) {
            return;
        }
        $this->matchedRules[] = $rule;
    }

    /**
     * @return iterable<FunctionLikeCallCount>
     */
    public function children(): iterable
    {
        yield from $this->children;
    }

    public function markIgnored(string $rule): void
    {
        $this->ignoredRules[] = $rule;
    }
}
