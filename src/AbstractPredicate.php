<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\Contracts\FailureAwareContract;
use Sonergia\Predicates\Contracts\PredicateContract;
use Sonergia\Predicates\Contracts\ResolutionAwareContract;
use Sonergia\Predicates\Traits\ResolutionAwareTrait;
use Exception;

abstract class AbstractPredicate implements PredicateContract, FailureAwareContract, ResolutionAwareContract, \Countable
{
    use ResolutionAwareTrait;

    public const PREDICATE_JOIN_AND = 0;
    public const PREDICATE_JOIN_OR = 1;
    public const PREDICATE_JOINS = [
        self::PREDICATE_JOIN_AND,
        self::PREDICATE_JOIN_OR
    ];

    /**
     * @return bool
     */
    public function __invoke()
    {
        return $this->evaluate();
    }

    abstract public function count(): int;

    /**
     * @return bool
     */
    abstract protected function resolve(): bool;

    /**
     * @return bool
     */
    final public function evaluate(): bool
    {
        if (!$this->hasBeenResolved()) {
            $this->checkResolvability()
                ->setResolvedValue(
                    $this->resolve()
                );
        }
        return $this->getResolvedValue();
    }

    /**
     * Must be called before resolve method
     * @return self
     */
    protected function checkResolvability(): self
    {
        return $this;
    }


    /**
     * @param AbstractPredicate
     * @return AbstractPredicate
     * @throws Exception
     */
    public function and(AbstractPredicate $predicate): self
    {
        return new Aggregate([$this, $predicate], static::PREDICATE_JOIN_AND);
    }

    /**
     * @param AbstractPredicate
     * @return AbstractPredicate
     * @throws Exception
     */
    public function or(AbstractPredicate $predicate): self
    {
        return new Aggregate([$this, $predicate], static::PREDICATE_JOIN_OR);
    }

    /**
     * @return AbstractPredicate[]|array
     */
    public function getFailures(): array
    {
        // le prédicat n'a pas été résolu ou est vrai, pas d'erreurs
        if (!$this->hasBeenResolved() || $this->isTrue()) {
            return [];
        }
        // le prédicat est responsable de l'échec de sa résolution (la logique est différente pour l'aggrégat)
        return [$this];
    }
}
