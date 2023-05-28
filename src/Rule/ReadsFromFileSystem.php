<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class ReadsFromFileSystem implements Rule
{
    public function reasoning(): string
    {
        return 'Accessing the file system for reading does not scale well, but is harmless in small amounts';
    }

    public function cost(): Cost
    {
        return Cost::MEDIUM_LOW;
    }

    public function applies(Node $astNode): bool
    {
        if (!($astNode instanceof Node\Expr\FuncCall)) {
            return false;
        }
        if (!($astNode->name instanceof Node\Name)) {
            return false;
        }
        if ($astNode->name->toLowerString() === 'file_get_contents') {
            return true;
        }
        if ($astNode->name->toLowerString() === 'scandir') {
            return true;
        }
        if ($astNode->name->toLowerString() === 'is_dir') {
            return true;
        }
        if ($astNode->name->toLowerString() === 'is_file') {
            return true;
        }
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BEST_PRACTICES;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return PHPEnvironment::CLI !== $phpEnvironment;
    }
}
