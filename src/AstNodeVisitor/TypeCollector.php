<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\TypeList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class TypeCollector extends NodeVisitorAbstract
{
    private string $class = '';
    public function __construct(
        private readonly TypeList $types,
    ) {
    }
    public function enterNode(Node $node): null
    {
        if ($node instanceof Node\Stmt\ClassLike && $node->namespacedName instanceof Node\Identifier) {
            $this->types->addVariable($node->namespacedName->toString(), '__construct', 'this', $node->namespacedName->toString());
            $this->class = $node->namespacedName->toString();
        }
        if ($node instanceof Node\Stmt\Property) {
            foreach ($node->props as $property) {
                if (property_exists($property, 'type') && $property->type instanceof Node\Identifier) {
                    $this->types->addProperty($this->class, $property->name->toString(), $property->type->toString());
                }
            }
        }
        if ($node instanceof Node\Stmt\ClassMethod && $node->returnType instanceof Node\Identifier) {
            $this->types->addMethod($this->class, $node->name->toString(), $node->returnType->toString());
        }
        if ($node instanceof Node\Stmt\Function_ && $node->returnType instanceof Node\Identifier) {
            $this->types->addFunction($node->namespacedName->toString(), $node->returnType->toString());
        }
        return null;
    }
}