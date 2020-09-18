<?php

namespace Sonergia\Predicates;

class NotPredicate extends AbstractPredicate
{
    /**
     * @var AbstractPredicate
     */
    private $predicate;

    /**
     * NotPredicate constructor.
     * @param AbstractPredicate $predicate
     */
    public function __construct(AbstractPredicate $predicate)
    {
        $this->setPredicate($predicate);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * @param AbstractPredicate $predicate
     * @return $this
     */
    private function setPredicate(AbstractPredicate $predicate): self
    {
        $this->predicate = $predicate;
        return $this;
    }

    /**
     * @return bool
     */
    protected function resolve(): bool
    {
        return !$this->predicate->evaluate();
    }
}
