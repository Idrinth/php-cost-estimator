<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use De\Idrinth\PhpCostEstimator\State\TypeList;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class TypeResolver extends NodeVisitorAbstract
{
    private string $class = '';
    private array $properties = [];
    private array $variables = [];
    public function __construct(
        private readonly TypeList $types,
    ) {
    }
    public function enterNode(Node $node): Node
    {
        if ($node instanceof Node\Stmt\ClassLike && $node->namespacedName instanceof Node\Name) {
            $this->class = $node->namespacedName->toString();
            $this->variables['this'] = $this->class;
        }
        if ($node instanceof Node\Stmt\Property) {
            foreach ($node->props as $property) {
                if (property_exists($property, 'type') && $property->type instanceof Node\Identifier) {
                    $this->properties[$property->name->toString()] = $node->type->toString();
                }
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
            } elseif ($expr instanceof Node\Expr\MethodCall && $var instanceof Node\Expr\Variable) {
                if ($expr->var instanceof Node\Expr\Variable && $expr->var->name === 'this' && $expr->name instanceof Node\Identifier) {
                    $this->variables[$var->name . ''] = $this->types->getMethodReturnType($this->class, $expr->name . '');
                }
            } elseif ($expr instanceof Node\Expr\PropertyFetch && $var instanceof Node\Expr\Variable) {
                if ($expr->var instanceof Node\Expr\Variable && $expr->var->name === 'this' && $expr->name instanceof Node\Identifier) {
                    $this->variables[$var->name . ''] = $this->types->getMethodReturnType($this->class, $expr->name . '');
                }
            } elseif ($expr instanceof Node\Expr\StaticCall && $var instanceof Node\Expr\Variable) {
                if ($expr->class instanceof Node\Name) {
                    $this->variables[$var->name . ''] = $this->types->getMethodReturnType($expr->class->toString(), $expr->name->toString());
                } elseif ($expr->class instanceof Node\Stmt\Class_) {
                    $this->variables[$var->name . ''] = $this->types->getMethodReturnType($expr->class->name->toString(), $expr->name->toString());
                }
            } elseif ($expr instanceof Node\Expr\StaticPropertyFetch && $var instanceof Node\Expr\Variable) {
                if ($expr->class instanceof Node\Name) {
                    $this->variables[$var->name . ''] = $this->types->getPropertyType($expr->class->toString(), $expr->name->toString());
                } elseif ($expr->class instanceof Node\Stmt\Class_) {
                    $this->variables[$var->name . ''] = $this->types->getPropertyType($expr->class->name->toString(), $expr->name->toString());
                }
            } elseif ($expr instanceof Node\Expr\FuncCall && $var instanceof Node\Expr\Variable) {
                if ($expr->name instanceof Node\Name) {
                    $this->variables[$var->name . ''] = $this->types->getFunctionReturnType($expr->name->toString());
                }
            }
        }
        if ($node instanceof Node\Expr\Variable) {
            if (isset($this->variables[$node->name . ''])) {
                $node->setAttribute('idrinth-type', $this->variables[$node->name . '']);
            } else {
                #var_dump($node->name, $this->variables, $this->class);
                #die;
            }
        }
        if ($node instanceof Node\Expr\PropertyFetch) {
            if ($node->var instanceof Node\Identifier && $node->var->name === 'this' && isset($this->properties[$node->name . ''])) {
                $node->setAttribute('idrinth-type', $this->properties[$node->name . '']);
            }
        }
        return $node;
    }
    public function leaveNode(Node $node): null
    {
        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->variables = [];
            $this->variables['this'] = $this->class;
        }
        if ($node instanceof Node\Stmt\Function_) {
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