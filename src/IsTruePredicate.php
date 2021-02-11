<?php

namespace Sonergia\Predicates;

class IsTruePredicate extends AbstractPredicate
{
    /**
     * @return int
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * @return bool
     */
    protected function resolve(): bool
    {
        return true;
    }
}