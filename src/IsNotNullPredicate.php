<?php

namespace Sonergia\Predicates;

class IsNotNullPredicate extends AbstractPredicate
{
    private $var;

    /**
     * Exists constructor.
     * @param $var
     */
    public function __construct($var)
    {
        $this->var = $var;
    }

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
        return null !== $this->var;
    }
}
