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
    private function children(
        FunctionLike $callable,
        PHPEnvironment $environment,
        int $callCount = 1,
        int $indent = 0,
    ): Generator {
        $cost = $callable->cost($environment, $callCount);
        if ($cost === 0) {
            return;
        }
        $rules = [];
        foreach ($callable->matchedRules() as $rule) {
            if ($rule->relevant($environment)) {
                $rules[] = basename(get_class($rule));
            }
        }
        yield str_repeat('  ', $indent)
            . "{$callable->name()}@{$environment->name} => {$cost}"
            . implode(', ', $rules);
        foreach ($callable->children() as $child) {
            if ($child->count > 0) {
                yield from $this->children(
                    $child->callee,
                    $environment,
                    $child->count * $callCount,
                    $indent + 1
                );
            }
        }
    }
    public function getIterator(): Generator
    {
        foreach ($this->callables as $callable) {
            if ($callable->isRoot()) {
                $environment = $callable->environment();
                yield from $this->children($callable, $environment);
            }
        }
    }
    public function registerDefinition(
        string $name,
        ?PHPEnvironment $environment,
        Rule ...$matchedRules
    ): void {
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

    public function markStart(string $name, PHPEnvironment $environment): void
    {
        if (!isset($this->callables[$name])) {
            $this->callables[$name] = new FunctionLike($name);
        }
        $this->callables[$name]->markStart($environment);
    }
}
