<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use Generator;
use IteratorAggregate;

final class CallableList implements IteratorAggregate
{
    /**
     * @var FunctionLike[]
     */
    private array $callables = [];
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
    public function registerDefinition(string $name, ?PHPEnvironment $environment, Rule ...$matchedRules): void
    {
        if (!isset($this->callables[$name])) {
            $this->callables[$name] = new FunctionLike($name);
        }
        foreach ($matchedRules as $rule) {
            $this->callables[$name]->registerRule($rule);
        }
        if ($environment instanceof PHPEnvironment) {
            $this->callables[$name]->markStart($environment);
        }
    }

    public function registerCallee(string $context, string $called, int $count): void
    {
        if ($context === '') {
            return;
        }
        if (!isset($this->callables[$context])) {
            $this->callables[$context] = new FunctionLike($context);
        }
        if (!isset($this->callables[$called])) {
            $this->callables[$called] = new FunctionLike($called);
        }
        $this->callables[$context]->registerCallee($this->callables[$called], $count);
    }
}
