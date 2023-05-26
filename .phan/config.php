<?php

return [
    'warn_about_relative_include_statement' => true,
    'processes' => 1,
    'directory_list' => [
        'src',
        'bin',
        'vendor',
    ],
    'exclude_analysis_directory_list' => [
        'vendor/'
    ],
    'skip_slow_php_options_warning' => false,
    'autoload_internal_extension_signatures' => [
        'ast'         => '.phan/internal_stubs/ast.phan_php',
        'ctype'       => '.phan/internal_stubs/ctype.phan_php',
        'igbinary'    => '.phan/internal_stubs/igbinary.phan_php',
        'mbstring'    => '.phan/internal_stubs/mbstring.phan_php',
        'pcntl'       => '.phan/internal_stubs/pcntl.phan_php',
        'phar'        => '.phan/internal_stubs/phar.phan_php',
        'posix'       => '.phan/internal_stubs/posix.phan_php',
        'readline'    => '.phan/internal_stubs/readline.phan_php',
        'simplexml'   => '.phan/internal_stubs/simplexml.phan_php',
        'sysvmsg'     => '.phan/internal_stubs/sysvmsg.phan_php',
        'sysvsem'     => '.phan/internal_stubs/sysvsem.phan_php',
        'sysvshm'     => '.phan/internal_stubs/sysvshm.phan_php',
    ],
    'ignore_undeclared_functions_with_known_signatures' => false,
    'plugin_config' => [
        'php_native_syntax_check_max_processes' => 4,
        'has_phpdoc_method_ignore_regex' => '@^Phan\\\\Tests\\\\.*::(test.*|.*Provider)$@',
        'has_phpdoc_check_duplicates' => true,
        'empty_statement_list_ignore_todos' => true,
        'infer_pure_methods' => true,
        'regex_warn_if_newline_allowed_at_end' => true,
    ],
    'plugins' => [
        'AlwaysReturnPlugin',
        'DollarDollarPlugin',
        'UnreachableCodePlugin',
        'DuplicateArrayKeyPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
        'UseReturnValuePlugin',
        'UnknownElementTypePlugin',
        'DuplicateExpressionPlugin',
        'WhitespacePlugin',
        'InlineHTMLPlugin',
        'PHPDocToRealTypesPlugin',
        'PHPDocRedundantPlugin',
        'PreferNamespaceUsePlugin',
        'EmptyStatementListPlugin',
        'LoopVariableReusePlugin',
        'RedundantAssignmentPlugin',
        'StrictComparisonPlugin',
        'StrictLiteralComparisonPlugin',
        'ShortArrayPlugin',
        'SimplifyExpressionPlugin',
        'RemoveDebugStatementPlugin',
        'UnsafeCodePlugin',
        'DeprecateAliasPlugin',
    ],
];