<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use De\Idrinth\PhpCostEstimator\AstNodeVisitor\CallStackBuilder;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\ConfigurationReader;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\RuleChecker;
use De\Idrinth\PhpCostEstimator\Configuration\Cli;
use De\Idrinth\PhpCostEstimator\Configuration\File;
use De\Idrinth\PhpCostEstimator\Configuration\Merged;
use De\Idrinth\PhpCostEstimator\Control\StartingPoint;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\State\CallableList;
use De\Idrinth\PhpCostEstimator\State\RuleList;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends Command
{
    private Parser $parser;
    private NodeTraverserInterface $traverser;
    public function __construct()
    {
        parent::__construct('estimate-cost:check');
        $this->parser = new Parser\Php7(new Lexer());
        $this->traverser = new NodeTraverser();
    }
    protected function configure(): void
    {
        $this->setDescription('Checks the codebase for possible performance issues');
        $this->addOption('check-cleaned-dependencies', 'c', InputOption::VALUE_NONE, 'Check cleaned dependencies');
        $this->addOption('check-optimized-autoloader', 'o', InputOption::VALUE_NONE, 'Check optimized autoloader');
        $this->addOption('rule', 'r', InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY, 'Enable additional rules');
    }
    private function iterateFolder(string $folder, CallableList &$callables, OutputInterface $output): void
    {
        foreach (array_diff(scandir($folder), ['.', '..']) as $file) {
            $path = $folder . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->iterateFolder($path, $callables, $output);
                continue;
            }
            if (str_ends_with($file, '.php')) {
                $output->write('.');
                $this->traverser->traverse($this->parser->parse(file_get_contents($path)));
            }
        }
    }
    #[StartingPoint(PHPEnvironment::CLI, 1)]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = new Merged(new File(getcwd()), new Cli($input));
        $callables = new CallableList();
        $this->traverser->addVisitor(new CallStackBuilder($callables));
        $this->traverser->addVisitor(new ConfigurationReader($callables));
        $this->traverser->addVisitor(new RuleChecker($callables, new RuleList(...$config->ruleWhitelist())));
        $output->writeln('Parsing codebase');
        foreach ($config->foldersToScan() as $folder) {
            $this->iterateFolder(
                getcwd() . DIRECTORY_SEPARATOR . $folder,
                $callables,
                $output,
            );
        }
        $output->writeln('');
        $output->writeln('Writing results');
        foreach ($callables as $callable) {
            $output->writeln($callable);
        }
        return 0;
    }
}
