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

    public function phpVersion(): string
    {
        foreach ($this->configurations as $configuration) {
            $version = $configuration->phpVersion();
            if ($version !== '') {
                return $version;
            }
        }
        return '';
    }

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
        return 0;
    }
}
