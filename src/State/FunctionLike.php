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
                    Cost::LOW => $cost += 2,
                    Cost::MEDIUM => $cost += 8,
                    Cost::HIGH => $cost += 32,
                    Cost::VERY_LOW => $cost += 1,
                    Cost::MEDIUM_LOW => $cost += 4,
                    Cost::MEDIUM_HIGH => $cost += 16,
                    Cost::VERY_HIGH => $cost += 64,
                };
            }
        }
        foreach ($this->children as $child) {
            $cost += $child->callee->cost($environment) * $child->count;
        }
        return $cost * $callFactor;
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
    public function registerCallee(FunctionLike $caller, int $count = 1): void
    {
        $this->children[] = new FunctionLikeCallCount($caller, $count);
    }
    public function registerRule(Rule $rule): void
    {
        $this->matchedRules[] = $rule;
    }

    /**
     * @return iterable<FunctionLikeCallCount>
     */
    public function children(): iterable
    {
        yield from $this->children;
    }
}
