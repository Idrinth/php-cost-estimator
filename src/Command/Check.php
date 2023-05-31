<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use De\Idrinth\PhpCostEstimator\AstNodeVisitor\CallStackBuilder;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\ConfigurationReader;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\DeLooper;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\FallbackToRootNamespaceChecker;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\FunctionListBuilder;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\TypeResolver;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\RuleChecker;
use De\Idrinth\PhpCostEstimator\Configuration\Cli;
use De\Idrinth\PhpCostEstimator\Configuration\File;
use De\Idrinth\PhpCostEstimator\Configuration\Merged;
use De\Idrinth\PhpCostEstimator\Control\StartingPoint;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\State\CallableList;
use De\Idrinth\PhpCostEstimator\State\FunctionDefinitionList;
use De\Idrinth\PhpCostEstimator\State\RuleList;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends Command
{
    private Parser $parser;
    public function __construct()
    {
        parent::__construct('estimate-cost:check');
        $this->parser = new Parser\Php7(new Lexer());
    }
    protected function configure(): void
    {
        $this->setDescription('Checks the codebase for possible performance issues');
        $this->addOption(
            'check-cleaned-dependencies',
            'c',
            InputOption::VALUE_NONE,
            'Check cleaned dependencies'
        );
        $this->addOption(
            'check-optimized-autoloader',
            'o',
            InputOption::VALUE_NONE,
            'Check optimized autoloader'
        );
        $this->addOption(
            'rule',
            'r',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Enable additional rules'
        );
    }
    private function iterateFolder(string $folder, CallableList &$callables, OutputInterface $output, array $traversers): void
    {
        foreach (array_diff(scandir($folder), ['.', '..']) as $file) {
            $path = $folder . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->iterateFolder($path, $callables, $output, $traversers);
                continue;
            }
            if (str_ends_with($file, '.php')) {
                $output->write('.');
                $nodes = $this->parser->parse(file_get_contents($path));
                foreach ($traversers as $traverser) {
                    $output->write('#', false, OutputInterface::VERBOSITY_VERBOSE);
                    $nodes = $traverser->traverse($nodes);
                }
            }
        }
    }
    #[StartingPoint(PHPEnvironment::CLI, 1)]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = new Merged(new File(getcwd()), new Cli($input));
        $callables = new CallableList($config);
        $functions = new FunctionDefinitionList();
        $traversers = [];
        foreach ([
             new NameResolver(),
             new TypeResolver(),
             new DeLooper(),
             new FunctionListBuilder($functions),
             new FallbackToRootNamespaceChecker($functions),
             new ConfigurationReader($callables),
             new CallStackBuilder($callables),
             new RuleChecker($callables, new RuleList(...$config->ruleWhitelist()))
        ] as $visitor) {
            $traversers[] = new NodeTraverser();
            $traversers[count($traversers) -1]->addVisitor($visitor);
        }
        $output->writeln('Parsing codebase');
        foreach ($config->foldersToScan() as $folder) {
            $this->iterateFolder(
                getcwd() . DIRECTORY_SEPARATOR . $folder,
                $callables,
                $output,
                $traversers,
            );
        }
        $output->writeln('');
        $output->writeln('Parsing dependencies');
        $traversers = [];
        foreach ([
                     new NameResolver(),
                     new TypeResolver(),
                     new DeLooper(),
                     new FunctionListBuilder($functions),
                     new FallbackToRootNamespaceChecker($functions),
                     new CallStackBuilder($callables),
                     new RuleChecker($callables, new RuleList(...$config->ruleWhitelist()))
                 ] as $visitor) {
            $traversers[] = new NodeTraverser();
            $traversers[count($traversers) -1]->addVisitor($visitor);
        }
        $this->iterateFolder(
            getcwd() . DIRECTORY_SEPARATOR . 'vendor',
            $callables,
            $output,
            $traversers,
        );
        $output->writeln('');
        $output->writeln('Writing results');
        foreach ($callables as $callable) {
            $output->writeln($callable);
        }
        return 0;
    }
}
