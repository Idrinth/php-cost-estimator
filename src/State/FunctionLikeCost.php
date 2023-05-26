<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Rule;

final class FunctionLikeCost
{
    /**
     * @var Rule[]
     */
    private array $matchedRules;

    public function __construct(
        private readonly string $name,
        private readonly int $cost,
        Rule ...$matchedRules,
    ) {
        $this->matchedRules = $matchedRules;
    }
    public function name(): string
    {
        return $this->name;
    }
    public function cost(): int
    {
        return $this->cost;
    }
    public function matchedRules(): array
    {
        return $this->matchedRules;
    }
}