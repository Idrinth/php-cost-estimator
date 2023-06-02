<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Command;

use De\Idrinth\PhpCostEstimator\AstNodeVisitor\CallStackBuilder;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\ConfigurationReader;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\DeLooper;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\FallbackToRootNamespaceChecker;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\FunctionListBuilder;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\InheritanceLister;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\TypeResolver;
use De\Idrinth\PhpCostEstimator\AstNodeVisitor\RuleChecker;
use De\Idrinth\PhpCostEstimator\Configuration;
use De\Idrinth\PhpCostEstimator\Configuration\Cli;
use De\Idrinth\PhpCostEstimator\Configuration\File;
use De\Idrinth\PhpCostEstimator\Configuration\Merged;
use De\Idrinth\PhpCostEstimator\Control\StartingPoint;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\State\CallableList;
use De\Idrinth\PhpCostEstimator\State\FunctionDefinitionList;
use De\Idrinth\PhpCostEstimator\State\InheritanceList;
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
        $this->addOption(
            'no-progress',
            'p',
            InputOption::VALUE_NONE,
            'Disable progress output'
        );
    }
    private function iterateFolder(string $folder, CallableList &$callables, OutputInterface $output, array $traversers, bool $noProgress, array $dirBlacklist= ['bin', 'public', 'example', 'examples', 'test', 'tests']): void
    {
        foreach ($dirBlacklist as $blacklisted) {
            if (str_ends_with(strtolower($folder), DIRECTORY_SEPARATOR . $blacklisted)) {
                return;
            }
        }
        foreach (array_diff(scandir($folder), ['.', '..']) as $file) {
            $path = $folder . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->iterateFolder($path, $callables, $output, $traversers, $noProgress, $dirBlacklist);
                continue;
            }
            if (str_ends_with($file, '.php')) {
                $noProgress ?: $output->write('.');
                $nodes = $this->parser->parse(file_get_contents($path));
                foreach ($traversers as $traverser) {
                    $noProgress ?: $output->write('#', false, OutputInterface::VERBOSITY_VERBOSE);
                    $nodes = $traverser->traverse($nodes);
                }
            }
        }
    }
    public function iterateOverProject(OutputInterface $output, CallableList $callables, Configuration $config, FunctionDefinitionList $functions, InheritanceList $inheritanceList, bool $noProgress): void
    {
        $noProgress ?: $output->writeln('Parsing project');
        $traversers = [];
        foreach ([
                     new NameResolver(),
                     new TypeResolver(),
                     new DeLooper(),
                     new InheritanceLister($inheritanceList),
                     new FunctionListBuilder($functions, $callables),
                     new FallbackToRootNamespaceChecker($functions),
                     new ConfigurationReader($callables),
                     new CallStackBuilder($callables),
                     new RuleChecker($callables, new RuleList(...$config->ruleWhitelist()))
                 ] as $visitor) {
            $traversers[] = new NodeTraverser();
            $traversers[count($traversers) -1]->addVisitor($visitor);
        }
        foreach ($config->foldersToScan() as $folder) {
            $this->iterateFolder(
                getcwd() . DIRECTORY_SEPARATOR . $folder,
                $callables,
                $output,
                $traversers,
                $noProgress,
            );
        }
        $noProgress ?: $output->writeln('');
    }
    private function iterateOverDependencies(OutputInterface $output, CallableList $callables, Configuration $config, FunctionDefinitionList $functions, InheritanceList $inheritanceList, bool $noProgress): void
    {
        $noProgress ?: $output->writeln('Parsing dependencies');
        $traversers = [];
        foreach ([
                     new NameResolver(),
                     new TypeResolver(),
                     new DeLooper(),
                     new InheritanceLister($inheritanceList),
                     new FunctionListBuilder($functions, $callables),
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
            $noProgress,
        );
        $noProgress ?: $output->writeln('');
    }
    private function iterateOverResults(CallableList $callables, OutputInterface $output): void
    {
        $output->writeln('Writing results');
        foreach ($callables as $callable) {
            $output->writeln($callable);
        }
    }
    #[StartingPoint(PHPEnvironment::CLI, 10)]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = new Merged(new File(getcwd()), new Cli($input));
        $inheritanceList = new InheritanceList();
        $callables = new CallableList($config, $inheritanceList);
        $functions = new FunctionDefinitionList();
        $this->iterateOverProject($output, $callables, $config, $functions, $inheritanceList, (bool) $input->getOption('no-progress'));
        $this->iterateOverDependencies($output, $callables, $config, $functions, $inheritanceList, (bool) $input->getOption('no-progress'));
        $this->iterateOverResults($callables, $output);
        return 0;
    }
}
