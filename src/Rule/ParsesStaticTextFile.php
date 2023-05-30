<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Rule;

use De\Idrinth\PhpCostEstimator\Cost;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;
use De\Idrinth\PhpCostEstimator\Rule;
use De\Idrinth\PhpCostEstimator\RuleSet;
use PhpParser\Node;

final class ParsesStaticTextFile implements Rule
{
    public function reasoning(): string
    {
        return 'It is an unnecessary effort to parse text(ini,json,yaml,xml) files if the values don\'t change.';
    }

    public function cost(): Cost
    {
        return Cost::MEDIUM_HIGH;
    }

    public function applies(Node $astNode): bool
    {
        return false;
    }

    public function set(): RuleSet
    {
        return RuleSet::BUILD_PROCESS_ISSUE;
    }

    public function relevant(PHPEnvironment $phpEnvironment): bool
    {
        return PHPEnvironment::CLI !== $phpEnvironment;
    }
}
