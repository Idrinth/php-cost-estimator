<?php

namespace De\Idrinth\PhpCostEstimator;

use PhpParser\Node;

interface Rule
{
    public function reasoning(): string;
    public function cost(): Cost;
    public function applies(Node $astNode, PHPEnvironment $phpEnvironment): bool;
    public function set(): RuleSet;
}