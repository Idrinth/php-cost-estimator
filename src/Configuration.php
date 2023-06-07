<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator;

interface Configuration
{
    /**
     * @return iterable<Rule>
     */
    public function ruleWhitelist(): iterable;
    /**
     * @return iterable<string>
     */
    public function foldersToScan(): iterable;
    /**
     * @return iterable<string>
     */
    public function starters(): iterable;
    public function checkCleanedDependencies(): bool;
    public function checkOptimizedAutoloader(): bool;
    public function minSeverity(): int;
}
