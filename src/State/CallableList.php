<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Configuration;
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
    public function __construct(
        private readonly Configuration $configuration,
        private readonly InheritanceList $inheritanceList,
    ){
    }
    private function children(
        FunctionLike $callable,
        PHPEnvironment $environment,
        int|float $callCount = 1,
        int $recursionDepth = 5,
        int $indent = 0,
        array $previous = []
    ): Generator {
        $cost = $callable->cost($environment, $callCount, $recursionDepth);
        if ($cost < $this->configuration->minSeverity()) {
            return;
        }
        $rules = [];
        foreach ($callable->matchedRules() as $rule) {
            if ($rule->relevant($environment)) {
                $parts = explode('\\', get_class($rule));
                $rules[] = array_pop($parts);
            }
        }
        $indentation = str_repeat('  ', $indent);;
        yield $indentation
            . number_format($callCount) . "x{$callable->name()}@{$environment->name} => " . number_format($cost)
            . (count($rules) > 0 ? "\n$indentation- "  . implode("\n$indentation- ", array_unique($rules)) : '');
        $previous[$callable->name()] = ($previous[$callable->name()] ?? 0) + 1;
        if (!$callable->isFound() && str_contains($callable->name(), '\\')) {
            foreach ($callable->implementations() as $implementation) {
                yield from $this->children(
                    $implementation,
                    $environment,
                    $callCount,
                    $recursionDepth,
                    $indent + 1,
                    $previous,
                );
            }
            return;
        }
        foreach ($callable->children() as $child) {
            yield from $this->processChild($child, $environment, $callCount, $recursionDepth, $indent, $previous);
        }
    }
    private function processChild(FunctionLikeCallCount $child, PHPEnvironment $environment, int $callCount, int $recursionDepth, int $indent, array $previous): Generator
    {
        if ($child->count < 1) {
            return;
        }
        if (($previous[$child->callee->name()] ?? 0) > $recursionDepth) {
            yield str_repeat('  ', $indent+1) . "{$callCount}x{$child->callee->name()}@{$environment->name} => {-recursion-}";
            return;
        }
        yield from $this->children(
            $child->callee,
            $environment,
            $child->count * $callCount,
            $recursionDepth,
            $indent + 1,
            $previous,
        );
    }
    public function getIterator(): Generator
    {
        foreach ($this->callables as $callable) {
            if ($callable->isRoot()) {
                $environment = $callable->environment();
                yield from $this->children($callable, $environment, $callable->callFactor(), $callable->recursionDepth());
            }
        }
        foreach ($this->configuration->starters() as $starter => $environment) {
            if (isset($this->callables[$starter]) && !$this->callables[$starter]->isRoot()) {
                yield from $this->children($this->callables[$starter], $environment, 1, 5);
            }
        }
    }
    public function registerDefinition(
        string $name,
        Rule ...$matchedRules
    ): void {
        if (!isset($this->callables[$name])) {
            $this->callables[$name] = new FunctionLike($name, $this->inheritanceList, $this);
        }
        foreach ($matchedRules as $rule) {
            $this->callables[$name]->registerRule($rule);
        }
    }

    public function registerCallee(string $context, string $called, int $count): void
    {
        if ($context === '') {
            return;
        }
        if (!isset($this->callables[$context])) {
            $this->callables[$context] = new FunctionLike($context, $this->inheritanceList, $this);
        }
        if (!isset($this->callables[$called])) {
            $this->callables[$called] = new FunctionLike($called, $this->inheritanceList, $this);
        }
        $this->callables[$context]->registerCallee($this->callables[$called], $count);
    }

    public function markStart(string $name, PHPEnvironment $environment, int $callFactor, int $recursionDepth): void
    {
        if (!isset($this->callables[$name])) {
            $this->callables[$name] = new FunctionLike($name, $this->inheritanceList, $this);
        }
        $this->callables[$name]->markStart($environment, $callFactor, $recursionDepth);
    }

    public function markIgnored(string $name, string $rule): void
    {
        if (!isset($this->callables[$name])) {
            $this->callables[$name] = new FunctionLike($name, $this->inheritanceList, $this);
        }
        $this->callables[$name]->markIgnored($rule);
    }

    public function has(string $name): bool
    {
        return isset($this->callables[$name]);
    }

    public function get(string $name): FunctionLike
    {
        return $this->callables[$name];
    }

    public function markFound(string $string): void
    {
        if (!isset($this->callables[$string])) {
            $this->callables[$string] = new FunctionLike($string, $this->inheritanceList, $this);
        }
        $this->callables[$string]->markFound();
    }
}
