<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Rule;
use Stringable;

final class CallCost implements Stringable
{
    /**
     * @var Rule[]
     */
    private array $rules;

    public function __construct(
        private readonly string $name,
        private readonly int $cost,
        private readonly int $calls,
        Rule ...$matchedRules,
    ) {
        $this->rules = array_map(function (Rule $rule) {
            return $rule->name();
        }, $matchedRules);
    }
    public function cost(): int
    {
        return $this->cost * $this->calls;
    }
    public function name(): string
    {
        return $this->name;
    }
    public function __toString(): string
    {
        $cost = $this->cost * $this->calls;
        return "{$this->name}: ({$cost})\n  ".implode("\n  ", $this->rules);
    }
}