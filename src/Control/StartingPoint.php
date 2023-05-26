<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Control;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
final class StartingPoint
{
    public function __construct(
        public readonly string $phpEnvironment,
        public readonly int $callFactor,
    ) {
    }
}
