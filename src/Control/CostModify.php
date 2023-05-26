<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Control;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE| Attribute::TARGET_METHOD| Attribute::TARGET_FUNCTION)]
final class CostModify
{
    public function __construct(
        private readonly string $rule,
        private readonly int $cost,
    ) {
    }
}