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
        'De\\Idrinth\\PhpCostEstimator\\Rule\\UnnecessaryTypeDeclarations',
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