<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

class TypeList
{
    private array $returns = [];
    private array $variables = [];
    private array $properties = [];
    private array $functions = [];
    public function __construct(private readonly InheritanceList $inheritanceList)
    {
    }

    public function addProperty(string $class, string $property, string $type): void
    {
        $this->properties[$class] = $this->returns[$class] ?? [];
        $this->properties[$class][$property] = $type;
    }
    public function addVariable(string $class, string $method, string $variable, string $type): void
    {
        $this->variables[$class] = $this->variables[$class] ?? [];
        $this->variables[$class][$method] = $this->variables[$class][$method] ?? [];
        $this->variables[$class][$method][$variable] = $type;
    }
    public function addMethod(string $class, string $method, string $returnType): void
    {
        $this->returns[$class] = $this->returns[$class] ?? [];
        $this->returns[$class][$method] = $returnType;
    }
    public function getMethodReturnType(string $class, string $method): ?string
    {
        if (isset($this->returns[$class][$method])) {
            return $this->returns[$class][$method];
        }
        foreach ($this->inheritanceList->getInheritors($class) as $related) {
            if (isset($this->returns[$related][$method])) {
                return $this->returns[$related][$method];
            }
        }
        return null;
    }
    public function getVariableType(string $class, string $method, string $variable): ?string
    {
        return $this->variables[$class][$method][$variable] ?? null;
    }
    public function getPropertyType(string $class, string $property): ?string
    {
        return $this->properties[$class][$property] ?? null;
    }

    public function addFunction(string $name, string $returnType): void
    {
        $this->functions[$name] = $returnType;
    }
    public function getFunctionReturnType(string $name): ?string
    {
        return $this->functions[$name] ?? null;
    }
}