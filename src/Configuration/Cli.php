<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Configuration;

use De\Idrinth\PhpCostEstimator\Configuration;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Input\InputInterface;

final class Cli implements Configuration
{
    public function __construct(
        private readonly InputInterface $input
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
}