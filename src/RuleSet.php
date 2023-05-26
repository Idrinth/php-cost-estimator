<?php

namespace De\Idrinth\PhpCostEstimator;

enum RuleSet
{
    case BEST_PRACTICES;
    case CONTROVERSIAL;
    case MICRO_OPTIMISATIONS;
    case BUILD_PROCESS_ISSUE;
    case DESIGN_FLAW;
}
