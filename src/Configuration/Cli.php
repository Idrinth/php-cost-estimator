<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Configuration;

use De\Idrinth\PhpCostEstimator\Configuration;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Input\InputInterface;

final readonly class Cli implements Configuration
{
    public function __construct(
        private InputInterface $input
    ) {
    }
    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function ruleWhitelist(): iterable
    {
        foreach ($this->input->getOption('rule') as $rule) {
            yield (new ReflectionClass("\\De\\Idrinth\\PhpCostEstimator\\Rule\\$rule"))->newInstance();
        }
    }

    public function phpVersion(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function foldersToScan(): iterable
    {
        return [];
    }

    public function checkCleanedDependencies(): bool
    {
        return $this->input->hasOption('check-cleaned-dependencies');
    }

    public function checkOptimizedAutoloader(): bool
    {
        return $this->input->hasOption('check-optimized-autoloader');
    }

    public function minSeverity(): int
    {
        return (int) $this->input->getOption('min-severity');
    }
}
