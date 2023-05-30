<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class TypeResolver extends NodeVisitorAbstract
{
    private string $class = '';
    private array $properties = [];
    private array $variables = [];
    public function enterNode(Node $node): Node
    {
        if ($node instanceof Node\Stmt\ClassLike) {
            $this->class = $node->namespacedName->toString();
            $this->variables['this'] = $this->class;
        }
        if ($node instanceof Node\Stmt\Property) {
            foreach ($node->props as $property) {
                $this->properties[$property->name->toString()] = $node->type->toString();
            }
        }
        if ($node instanceof Node\Expr\Assign) {
            $expr = $node->expr;
            $var = $node->var;
            if ($expr instanceof Node\Expr\New_ && $var instanceof Node\Expr\Variable) {
                if ($expr->class instanceof Node\Name) {
                    $this->variables[$var->name . ''] = $expr->class->toString();
                } elseif ($expr->class instanceof Node\Stmt\Class_) {
                    $this->variables[$var->name . ''] = $expr->class->name->toString();
                }
            } elseif ($expr instanceof Node\Expr\New_ && $var instanceof Node\Identifier) {
                if ($expr->class instanceof Node\Name) {
                    $this->variables[$var->name . ''] = $expr->class->toString();
                } elseif ($expr->class instanceof Node\Stmt\Class_) {
                    $this->variables[$var->name . ''] = $expr->class->name->toString();
                }
            }
        }
        if ($node instanceof Node\Param) {
            $this->variables[$node->var->name] = $node->type->toString();
        }
        if ($node instanceof Node\Expr\Variable) {
            if (isset($this->variables[$node->name . ''])) {
                $node->setAttribute('idrinth-type', $this->variables[$node->name . '']);
            }
        }
        return $node;
    }
    public function leaveNode(Node $node): null
    {
        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->variables = [];
        }
        return null;
    }

    public function afterTraverse(array $nodes): null
    {
        $this->variables = [];
        $this->class = '';
        $this->properties = [];
        return null;
    }
}