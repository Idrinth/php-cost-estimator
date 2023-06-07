<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Configuration;

use De\Idrinth\PhpCostEstimator\Configuration;

final class Merged implements Configuration
{
    /**
     * @var Configuration[]
     */
    private array $configurations;

    public function __construct(Configuration ...$configurations)
    {
        $this->configurations = $configurations;
    }
    public function ruleWhitelist(): iterable
    {
        foreach ($this->configurations as $configuration) {
            yield from $configuration->ruleWhitelist();
        }
    }

    /**
     * @return iterable<string>
     */
    public function foldersToScan(): iterable
    {
        foreach ($this->configurations as $configuration) {
            yield from $configuration->foldersToScan();
        }
    }

    public function checkCleanedDependencies(): bool
    {
        foreach ($this->configurations as $configuration) {
            if ($configuration->checkCleanedDependencies()) {
                return true;
            }
        }
        return false;
    }

    public function checkOptimizedAutoloader(): bool
    {
        foreach ($this->configurations as $configuration) {
            if ($configuration->checkOptimizedAutoloader()) {
                return true;
            }
        }
        return false;
    }

    public function minSeverity(): int
    {
        if (count($this->configurations) === 0) {
            return 0;
        }
        return max(0, ...array_map(fn (Configuration $configuration): int => $configuration->minSeverity(), $this->configurations));
    }

    public function starters(): iterable
    {
        foreach ($this->configurations as $configuration) {
            yield from $configuration->starters();
        }
    }
}
