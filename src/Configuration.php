<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator;

interface Configuration
{
    /**
     * @return iterable<Rule>
     */
    public function ruleWhitelist(): iterable;
    public function phpVersion(): string;

    /**
     * @return iterable<string>
     */
    public function foldersToScan(): iterable;
    public function checkCleanedDependencies(): bool;
    public function checkOptimizedAutoloader(): bool;
    public function minSeverity(): int;
}
