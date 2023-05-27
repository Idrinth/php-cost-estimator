<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Control;

use Attribute;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
final class StartingPoint
{
    public function __construct(
        public readonly PHPEnvironment $phpEnvironment,
        public readonly int $callFactor,
    ) {
    }
}
