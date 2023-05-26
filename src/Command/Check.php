<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

class Check extends Command
{
    public function __construct()
    {
        parent::__construct('estimate-cost:check');
    }
    protected function configure(): void
    {
        $this->setDescription('Checks the codebase for possible performance issues');
        $this->addOption('check-cleaned-dependencies', 'c', InputOption::VALUE_NONE, 'Check cleaned dependencies');
        $this->addOption('check-optimized-autoloader', 'o', InputOption::VALUE_NONE, 'Check optimized autoloader');
    }
}