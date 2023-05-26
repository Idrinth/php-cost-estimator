<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\Control;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class AssumedSize
{
    public function __construct(
        private readonly int $elements,
    ) {
    }
}
