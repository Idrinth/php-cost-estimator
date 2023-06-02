<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use De\Idrinth\PhpCostEstimator\Configuration\Cli;
use De\Idrinth\PhpCostEstimator\Configuration\File;
use De\Idrinth\PhpCostEstimator\Configuration\Merged;
use De\Idrinth\PhpCostEstimator\Control\StartingPoint;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Reason extends Command
{
    public function __construct()
    {
        parent::__construct('estimate-cost:reason');
    }
    protected function configure(): void
    {
        $this->setDescription('Lists all rules and their reasoning');
        $this->addArgument('rule', InputArgument::OPTIONAL, 'The rule to show reasoning for');
    }

    #[StartingPoint(PHPEnvironment::CLI)]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->hasArgument('rule')) {
            $rule = (new ReflectionClass($input->getArgument('rule')))->newInstance();
            $output->writeln(get_class($rule) . ': ' . $rule->reasoning());
            return 0;
        }
        $config = new File(getcwd());
        foreach ($config->ruleWhitelist() as $rule) {
            $output->writeln(get_class($rule) . ': ' . $rule->reasoning());
        }
        return 0;
    }
}