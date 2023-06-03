<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Control;

use Attribute;
use De\Idrinth\PhpCostEstimator\PHPEnvironment;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
final readonly class StartingPoint
{
    public function __construct(
        public PHPEnvironment $phpEnvironment = PHPEnvironment::BOTH,
        public int $callFactor = 1,
        public int $recursionDepth = 5,
    ) {
    }
}
