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
    /**
     * @var true
     */
    private bool $found = false;
    private int|float $cost = -1;
    private int $callfactor = 1;
    private int $recursionDepth = 5;

    public function __construct(
        private readonly string $name,
        private readonly InheritanceList $inheritanceList,
        private readonly CallableList $callableList,
    ) {
    }
    public function name(): string
    {
        return $this->name;
    }
    private function mayCheckCost(string $child, string $func): bool
    {
        return $this->callableList->has($child . '::' . $func) && $child . '::' . $func !== $this->name;
    }
    public function cost(PHPEnvironment $environment, int|float $callFactor = 1, int $recursionDepth = 5, array $recursionLimiter = []): int|float
    {
        if ($this->cost > -1) {
            return $this->cost * $callFactor;
        }
        $cost = 0;
        if (($recursionLimiter[$this->name] ?? 0) > $recursionDepth) {
            return $cost;
        }
        $recursionLimiter[$this->name] = ($recursionLimiter[$this->name] ?? 0) + 1;
        if (!$this->found && str_contains($this->name, '::') && str_contains($this->name, '\\')) {
            foreach ($this->inheritanceList->getInheritors(explode('::', $this->name)[0]) as $child) {
                if ($this->mayCheckCost($child, explode('::', $this->name)[1])) {
                    $callee = $this->callableList->get($child . '::' . explode('::', $this->name)[1]);
                    $cost += $callee->cost($environment, $callFactor, $recursionLimiter);
                }
            }
            return $cost;
        }
        if (!$this->found) {
            return $cost;
        }
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
            if (str_starts_with($child->callee->name(), 'parent::') || str_starts_with($child->callee->name(), 'self::') || str_starts_with($child->callee->name(), 'static::')) {
                foreach ($this->inheritanceList->getInheritors(explode('::', $this->name)[0]) as $alternative) {
                    if ($this->mayCheckCost($alternative, explode('::', $child->callee->name())[1])) {
                        $callee = $this->callableList->get($alternative . '::' . explode('::', $child->callee->name())[1]);
                        $cost += $callee->cost($environment, $callFactor, $recursionDepth, $recursionLimiter);
                    }
                }
                continue;
            }
            $cost += $child->callee->cost($environment, $child->count * $callFactor, $recursionDepth, $recursionLimiter);
        }
        $this->cost = $cost/$callFactor;
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
    public function markStart(PHPEnvironment $environment, int $callFactor, int $recursionDepth): void
    {
        $this->isStart = true;
        $this->environment = $environment;
        $this->callfactor = $callFactor;
        $this->recursionDepth = $recursionDepth;
    }
    public function registerCallee(FunctionLike $callee, int $count = 1): void
    {
        $this->markFound();
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
        $this->markFound();
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

    public function markFound(): void
    {
        $this->found = true;
    }

    public function isFound(): bool
    {
        return $this->found;
    }

    public function implementations(): iterable
    {
        foreach ($this->inheritanceList->getInheritors(explode('::', $this->name)[0]) as $child) {
            if ($this->callableList->has($child . '::' . explode('::', $this->name)[1])) {
                yield $this->callableList->get($child . '::' . explode('::', $this->name)[1]);
            }
        }
    }
    public function callFactor(): int
    {
        return $this->callfactor;
    }
    public function recursionDepth(): int
    {
        return $this->recursionDepth;
    }
}
