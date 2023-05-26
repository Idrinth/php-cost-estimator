<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Configuration;

use De\Idrinth\PhpCostEstimator\Configuration;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

final class File implements Configuration
{
    /**
     * @var string[]
     */
    private array $rules = [];
    private string $version = '';
    /**
     * @var string[]
     */
    private array $folders = [];
    /**
     * @var int|mixed
     */
    private int $minSeverity;

    public function __construct(string $workingDirectory)
    {
        if (!is_file($workingDirectory . '/.php-cost-estimator/config.php')) {
            return;
        }
        $data = require $workingDirectory . '/.php-cost-estimator/config.php';
        if (!is_array($data)) {
            throw new RuntimeException('Invalid config file');
        }
        $this->rules = $data['rules'] ?? [];
        $this->version = $data['version'] ?? '';
        $this->folders = $data['folders'] ?? [];
        $this->minSeverity = $data['minSeverity'] ?? 0;
    }

    /**
     * @throws ReflectionException
     */
    public function ruleWhitelist(): iterable
    {
        foreach ($this->rules as $rule) {
            yield (new ReflectionClass($rule))->newInstance();
        }
    }

    public function phpVersion(): string
    {
        return $this->version;
    }

    public function foldersToScan(): iterable
    {
        return $this->folders;
    }

    public function checkCleanedDependencies(): bool
    {
        return false;
    }

    public function checkOptimizedAutoloader(): bool
    {
        return false;
    }
    public function minSeverity(): int
    {
        return $this->minSeverity;
    }
}
