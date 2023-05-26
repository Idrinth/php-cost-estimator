<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

class FunctionLikeCallCount
{
    public function __construct(
        public readonly FunctionLike $callee,
        public int $count,
    ) {
    }
}
