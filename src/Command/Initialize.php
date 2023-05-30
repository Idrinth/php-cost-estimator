<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use De\Idrinth\PhpCostEstimator\Control\StartingPoint;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Initialize extends Command
{
    public function __construct()
    {
        parent::__construct('estimate-cost:initialize');
    }
    protected function configure(): void
    {
        $this->setDescription('Initializes the tool\'s configuration file with sensible defaults');
    }
    #[StartingPoint(PHPEnvironment::CLI, 1)]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = getcwd() . '/.php-cost-estimator/config.php';
        if (is_file($file)) {
            $output->writeln('Configuration file already exists');
            return 0;
        }
        $output->writeln('Initializing configuration file');
        mkdir(dirname($file));
        $rules = [];
        $output->writeln('Scanning for rules');
        foreach (scandir(__DIR__ . '/../Rule') as $rule) {
            if (str_ends_with($rule, '.php')) {
                $rules[] = 'De\\\\Idrinth\\\\PhpCostEstimator\\\\Rule\\\\' . substr($rule, 0, -4);
            }
        }
        $output->writeln(sprintf('Found %d rules', count($rules)));
        $output->writeln('Writing configuration file');
        $success = (bool) file_put_contents(
            $file,
            str_replace(
                [
                    '##RULES##',
                ],
                [
                    "'" . implode("',\n        '", $rules) . "',",
                ],
                file_get_contents(__DIR__ . '/../../.php-cost-estimator/default.php'),
            ),
        );
        $output->writeln($success ? 'Done' : 'Failed');
        return 0;
    }
}
