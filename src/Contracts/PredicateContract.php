<?php

namespace Sonergia\Predicates\Contracts;

interface PredicateContract
{

    /**
     * @return bool
     */
    public function evaluate(): bool;
}
