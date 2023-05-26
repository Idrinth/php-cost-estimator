<?php
declare(strict_types=1);

namespace De\Idrinth\PhpCostEstimator\State;

use De\Idrinth\PhpCostEstimator\Rule;
use Iterator;

final class RuleList implements Iterator
{
    private readonly array $rules;
    private int $position;
    public function __construct(Rule ...$rules)
    {
        $this->rules = $rules;
    }
    public function current(): Rule
    {
        return $this->rules[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->position < count($this->rules);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
    public function without(string ...$classnames): self
    {
        $rules = [];
        foreach ($this->rules as $rule) {
            if (!in_array(get_class($rule), $classnames, true)) {
                $rules[] = $rule;
            }
        }
        return new self(...$rules);
    }
}