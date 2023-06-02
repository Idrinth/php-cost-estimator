<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\InheritanceList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class InheritanceLister extends NodeVisitorAbstract
{
    public function __construct(
        private readonly InheritanceList $inheritanceList,
    ) {
    }
    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Stmt\Class_ && $node->namespacedName instanceof Node\Name) {
            if ($node->extends !== null) {
                $this->inheritanceList->addParent(
                    $node->extends->toString(),
                    $node->namespacedName->toString()
                );
            }
            foreach ($node->implements as $interface) {
                $this->inheritanceList->addInterface(
                    $interface . '',
                    $node->namespacedName->toString()
                );
            }
        }
        if ($node instanceof Node\Stmt\Interface_) {
            foreach ($node->extends as $interface) {
                if (!$node->namespacedName instanceof Node\Name) {
                    continue;
                }
                $this->inheritanceList->addInterface(
                    $interface->toString(),
                    $node->namespacedName->toString()
                );
            }
        }
    }
}