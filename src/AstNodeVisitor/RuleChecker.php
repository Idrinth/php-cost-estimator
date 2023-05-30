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
    private string $class = '';
    public function __construct(private readonly CallableList $callableList, private readonly RuleList $ruleList)
    {
    }
    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\ClassLike && $node->name instanceof Node\Identifier) {
            $this->class = $node->namespacedName->toString();
        }
        if ($node instanceof Node\Stmt\Function_ || $node instanceof Node\Stmt\ClassMethod) {
            $this->matchedRules = [];
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
        if ($node instanceof Node\Stmt\ClassLike) {
            $this->class = '';
        }
        if ($node instanceof Node\Stmt\Function_) {
            $this->callableList->registerDefinition(
                $node->namespacedName->toString(),
                ...$this->matchedRules
            );
            $this->matchedRules = [];
        }
        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->callableList->registerDefinition(
                $this->class . '::' . $node->name->toString(),
                ...$this->matchedRules
            );
            $this->matchedRules = [];
        }
        return null;
    }
    public function afterTraverse(array $nodes): null
    {
        $this->class = '';
        return null;
    }
}
