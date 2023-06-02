<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

final class InheritanceList
{
    private array $inheritors = [];
    public function addInterface(string $interface, string $implementer): void
    {
        $this->inheritors[$interface] = $this->inheritors[$interface] ?? [];
        $this->inheritors[$interface][] = $implementer;
    }
    public function addParent(string $parent, string $child): void
    {
        $this->inheritors[$parent] = $this->inheritors[$parent] ?? [];
        $this->inheritors[$parent][] = $child;
        $this->inheritors[$child] = $this->inheritors[$child] ?? [];
        $this->inheritors[$child][] = $parent;
    }
    public function getInheritors(string $class): array
    {
        return $this->inheritors[$class] ?? [];
    }
}