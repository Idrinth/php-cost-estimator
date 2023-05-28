<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Control;

use Attribute;

#[Attribute(flags: Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
final readonly class AssumedLoops
{
    public function __construct(
        public int $factor,
        public string $variableName,
    ) {
    }
}
