<?php

namespace De\Idrinth\PhpCostEstimator;

enum Cost
{
    case VERY_LOW;
    case LOW;
    case MEDIUM_LOW;
    case MEDIUM;
    case MEDIUM_HIGH;
    case HIGH;
    case VERY_HIGH;
}
