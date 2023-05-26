<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

use Generator;
use IteratorAggregate;

final class CallableList implements IteratorAggregate
{
    /**
     * @var FunctionLikeCost[]
     */
    private array $callables = [];
    /**
     * @var int[]
     */
    private array $calls = [];
    public function addDefinition(FunctionLikeCost $callable)
    {
        if ($callable->cost() > 0) {
            $this->callables[$callable->name()] = $callable;
        }
    }
    public function addCalls(string $name, int $calls = 1)
    {
        $this->calls[$name] = ($this->calls[$name] ?? 0) + $calls;
    }
    public function getIterator(): Generator
    {
        foreach ($this->callables as $callable) {
            if (($this->calls[$callable->name()] ?? 0) > 0) {
                yield new CallCost(
                    $callable->name(),
                    $callable->cost(),
                    $this->calls[$callable->name()] ?? 0,
                    ...$callable->matchedRules()
                );
            }
        }
    }
}