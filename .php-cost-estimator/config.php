<?php
declare(strict_types=1);

return [
    'rules' => [
        'De\\Idrinth\\PhpCostEstimator\\Rule\\LocksFileSystem',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\ParsesStaticTextFile',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\QueriesDatabase',
        'De\\Idrinth\\PhpCostEstimator\\Rule\\ReadsFromFileSystem',
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
    'folders' => [
        'src'
    ],
    "minSeverity" => 0,
];