<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use Symfony\Component\Console\Command\Command;

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
}