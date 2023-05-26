<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use De\Idrinth\PhpCostEstimator\Configuration\Cli;
use De\Idrinth\PhpCostEstimator\Configuration\File;
use De\Idrinth\PhpCostEstimator\Configuration\Merged;
use De\Idrinth\PhpCostEstimator\State\CallableList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Not yet implemented');
        $config = new Merged(new File(getcwd()), new Cli($input));
        $callables = new CallableList();
        return 0;
    }
}
