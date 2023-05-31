<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

final readonly class FunctionLikeCallCount
{
    public function __construct(
        public FunctionLike $callee,
        public int $count,
    ) {
    }
}
