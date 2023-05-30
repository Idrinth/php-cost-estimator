<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

class FunctionDefinitionList
{
    private array $functions = [];
    public function add(string $name): void
    {
        $this->functions[$name] = true;
    }
    public function has(string $name): bool
    {
        return isset($this->functions[$name]);
    }
}