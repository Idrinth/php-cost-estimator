<?php

declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\CallableList;
use De\Idrinth\PhpCostEstimator\State\RuleList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class RuleChecker extends NodeVisitorAbstract
{
    private array $matchedRules = [];
    private string $context = '';
    public function __construct(private CallableList $callableList, private readonly RuleList $ruleList)
    {
    }
    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\Function_ || $node instanceof Node\Stmt\ClassMethod) {
            $this->matchedRules = [];
            $this->context = $node->name->toString();
        }
        foreach ($this->ruleList as $rule) {
            if ($rule->applies($node)) {
                $this->matchedRules[] = $rule;
            }
        }
        return null;
    }
    public function leaveNode(Node $node): null
    {
        if ($node instanceof Node\Stmt\Function_ || $node instanceof Node\Stmt\ClassMethod) {
            $this->callableList->registerDefinition(
                $this->context,
                ...$this->matchedRules
            );
            $this->matchedRules = [];
            $this->context = '';
        }
        return null;
    }
}
