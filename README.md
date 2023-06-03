# PHP Cost Estimator

This small tool is being build to statically analyze your code for potential performance issues.

## Installation

```bash
composer require --global "php-cost-estimator/php-cost-estimator"
cd /your/project
estimate-cost estimate-cost:initialize
```

## Usage

```bash
cd /your/project
estimate-cost
```

### Output
The higher the number the more likely it is that the code is causing performance issues.

```
1xDe\Idrinth\PhpCostEstimator\Command\Check::execute@CLI => 189
- UsesFallbackToRootNamespace
  1xDe\Idrinth\PhpCostEstimator\Configuration\Merged::__construct@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Configuration\File::__construct@CLI => 2
  - UsesFallbackToRootNamespace
    1xis_file@CLI => 0
    1xis_array@CLI => 0
    1xRuntimeException::__construct@CLI => 0
  1xgetcwd@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Configuration\Cli::__construct@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\State\InheritanceList::__construct@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\State\CallableList::__construct@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\State\FunctionDefinitionList::__construct@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\State\TypeList::__construct@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Command\Check::iterateOverProject@CLI => 140
  - UsesFallbackToRootNamespace
    5xPhpParser\NodeTraverser::__construct@CLI => 0
    5xcount@CLI => 0
    5xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 130
    - UnnecessaryTypeDeclaration
    - UsesFallbackToRootNamespace
      50xstr_ends_with@CLI => 0
      25xstrtolower@CLI => 0
      25xis_dir@CLI => 0
      25xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 650
      - UnnecessaryTypeDeclaration
      - UsesFallbackToRootNamespace
        250xstr_ends_with@CLI => 0
        125xstrtolower@CLI => 0
        125xis_dir@CLI => 0
        125xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 3250
        - UnnecessaryTypeDeclaration
        - UsesFallbackToRootNamespace
          1250xstr_ends_with@CLI => 0
          625xstrtolower@CLI => 0
          625xis_dir@CLI => 0
          625xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 16250
          - UnnecessaryTypeDeclaration
          - UsesFallbackToRootNamespace
            6250xstr_ends_with@CLI => 0
            3125xstrtolower@CLI => 0
            3125xis_dir@CLI => 0
            3125xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 81250
            - UnnecessaryTypeDeclaration
            - UsesFallbackToRootNamespace
              31250xstr_ends_with@CLI => 0
              15625xstrtolower@CLI => 0
              15625xis_dir@CLI => 0
              15625xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 406250
              - UnnecessaryTypeDeclaration
              - UsesFallbackToRootNamespace
                156250xstr_ends_with@CLI => 0
                78125xstrtolower@CLI => 0
                78125xis_dir@CLI => 0
                15625xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => {-recursion-}
                78125xfile_get_contents@CLI => 0
              15625xfile_get_contents@CLI => 0
            3125xfile_get_contents@CLI => 0
          625xfile_get_contents@CLI => 0
        125xfile_get_contents@CLI => 0
      25xfile_get_contents@CLI => 0
    5xgetcwd@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Command\Check::iterateOverDependencies@CLI => 33
  - UnnecessaryTypeDeclaration
  - UsesFallbackToRootNamespace
    5xPhpParser\NodeTraverser::__construct@CLI => 0
    5xcount@CLI => 0
    1xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 26
    - UnnecessaryTypeDeclaration
    - UsesFallbackToRootNamespace
      10xstr_ends_with@CLI => 0
      5xstrtolower@CLI => 0
      5xis_dir@CLI => 0
      5xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 130
      - UnnecessaryTypeDeclaration
      - UsesFallbackToRootNamespace
        50xstr_ends_with@CLI => 0
        25xstrtolower@CLI => 0
        25xis_dir@CLI => 0
        25xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 650
        - UnnecessaryTypeDeclaration
        - UsesFallbackToRootNamespace
          250xstr_ends_with@CLI => 0
          125xstrtolower@CLI => 0
          125xis_dir@CLI => 0
          125xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 3250
          - UnnecessaryTypeDeclaration
          - UsesFallbackToRootNamespace
            1250xstr_ends_with@CLI => 0
            625xstrtolower@CLI => 0
            625xis_dir@CLI => 0
            625xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 16250
            - UnnecessaryTypeDeclaration
            - UsesFallbackToRootNamespace
              6250xstr_ends_with@CLI => 0
              3125xstrtolower@CLI => 0
              3125xis_dir@CLI => 0
              3125xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => 81250
              - UnnecessaryTypeDeclaration
              - UsesFallbackToRootNamespace
                31250xstr_ends_with@CLI => 0
                15625xstrtolower@CLI => 0
                15625xis_dir@CLI => 0
                3125xDe\Idrinth\PhpCostEstimator\Command\Check::iterateFolder@CLI => {-recursion-}
                15625xfile_get_contents@CLI => 0
              3125xfile_get_contents@CLI => 0
            625xfile_get_contents@CLI => 0
          125xfile_get_contents@CLI => 0
        25xfile_get_contents@CLI => 0
      5xfile_get_contents@CLI => 0
    1xgetcwd@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Command\Check::analyzeProject@CLI => 6
  - UnnecessaryTypeDeclaration
  - UsesFallbackToRootNamespace
    5xPhpParser\NodeTraverser::__construct@CLI => 0
    5xcount@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Command\Check::analyzeDependencies@CLI => 6
  - UnnecessaryTypeDeclaration
  - UsesFallbackToRootNamespace
    5xPhpParser\NodeTraverser::__construct@CLI => 0
    5xcount@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Command\Check::iterateOverResults@CLI => 1
  - UnnecessaryTypeDeclaration
1xDe\Idrinth\PhpCostEstimator\Command\Initialize::execute@CLI => 20
- UsesFallbackToRootNamespace
  1xgetcwd@CLI => 0
  1xis_file@CLI => 0
  1xmkdir@CLI => 0
  1xdirname@CLI => 0
  5xstr_ends_with@CLI => 0
  5xsubstr@CLI => 0
  1xsprintf@CLI => 0
  1xcount@CLI => 0
  1xfile_put_contents@CLI => 0
  1xstr_replace@CLI => 0
  1ximplode@CLI => 0
  1xfile_get_contents@CLI => 0
1xDe\Idrinth\PhpCostEstimator\Command\Reason::execute@CLI => 9
- UsesFallbackToRootNamespace
  1xReflectionClass::__construct@CLI => 0
  6xget_class@CLI => 0
  1xDe\Idrinth\PhpCostEstimator\Configuration\File::__construct@CLI => 2
  - UsesFallbackToRootNamespace
    1xis_file@CLI => 0
    1xis_array@CLI => 0
    1xRuntimeException::__construct@CLI => 0
  1xgetcwd@CLI => 0
```
## Configuration

The configuration file is located at `/your/project/.php-cost-estimator/config.php` and will be generated by the initialize command. Feel free to adjust it and commit it to your version control.

```php
<?php
declare(strict_types=1);

return [
    'rules' => [
        'De\\Idrinth\\PhpCostEstimator\\Rule\\BuildsUnusedObject',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\ConnectsToUnusedDatabase',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\LocksFileSystem',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\ParsesStaticTextFile',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\QueriesDatabase',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\ReadsFromFileSystem',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UnnecessaryCaching',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UnnecessaryTypeDeclaration',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UsesArrayKeyExists',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UsesFallbackToRootNamespace',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UsesInArrayOnLargeArray',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UsesReflection',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UsesRemoteCall',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UsesStaticComparison',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UsesVersionSwitches',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\WritesToFileSystem',
    ],
    'version' => '8.2.6',
    'folders' => [
        'src'
    ]
];
```

## Contributing

All contributions are welcome, see [CONTRIBUTING.md](CONTRIBUTING.md) for details.