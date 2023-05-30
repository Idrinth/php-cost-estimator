<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Control;

use Attribute;
use De\Idrinth\PhpCostEstimator\Cost;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
final readonly class CostModify
{
    public function __construct(
        public string $ruleClassName,
        public Cost $cost,
    ) {
    }
}
