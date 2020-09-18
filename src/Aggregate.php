<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\Exceptions\PredicateException;
use Sonergia\Predicates\Exceptions\UnresolvablePredicateException;
use Sonergia\Predicates\Traits\FailureAwareTrait;

class Aggregate extends AbstractPredicate
{
    use FailureAwareTrait;

    /** @var AbstractPredicate[]  */
    private $predicates;

    /** @var int */
    private $joinedBy;

    /**
     * Aggregate constructor.
     * @param array $predicates
     * @param int $joinedBy
     * @throws PredicateException
     */
    public function __construct(array $predicates, int $joinedBy = self::PREDICATE_JOIN_AND)
    {
        $this->predicates = [];
        $this->setPredicates($predicates)
            ->setJoinedBy($joinedBy);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->predicates);
    }

    /**
     * @param array $predicates
     * @return $this
     */
    private function setPredicates(array $predicates): self
    {
        foreach ($predicates as $predicate) {
            $this->addPredicate($predicate);
        }
        return $this;
    }

    /**
     * @param AbstractPredicate $predicate
     * @return $this
     */
    private function addPredicate(AbstractPredicate $predicate): self
    {
        $this->predicates[] = $predicate;
        return $this;
    }

    /**
     * @param int $joinedBy
     * @return $this
     * @throws PredicateException
     */
    private function setJoinedBy(int $joinedBy): self
    {
        if (!in_array($joinedBy, self::PREDICATE_JOINS)) {
            throw new PredicateException("Invalid argument supplied ($joinedBy), expect one of AbstractPredicate::SELF::PREDICATE_JOIN_AND or AbstractPredicate::SELF::PREDICATE_JOIN_OR");
        }
        $this->joinedBy = $joinedBy;
        return $this;
    }

    /**
     * @return bool
     */
    public function isJoinedByAnd(): bool
    {
        return self::PREDICATE_JOIN_AND === $this->joinedBy;
    }

    /**
     * @return bool
     */
    public function isJoinedByOr(): bool
    {
        return self::PREDICATE_JOIN_OR === $this->joinedBy;
    }

    /**
     * Must be called before resolve method
     * @return self
     * @throws UnresolvablePredicateException
     */
    protected function checkResolvability(): AbstractPredicate
    {
        if (!count($this)) {
            throw new UnresolvablePredicateException("can't resolve empty aggregate");
        }
        return $this;
    }

    /**
     * @return bool
     */
    protected function resolve(): bool
    {
        $this->resetFailures();
        if ($this->isJoinedByOr()) {
            return $this->resolveOr();
        }
        return $this->resolveAnd();
    }

    /**
     * @return bool
     */
    private function resolveAnd(): bool
    {
        $success = true;
        for ($i = 0, $countPredicates = count($this->predicates); $success && $i < $countPredicates; $i++) {
            $currentPredicate = $this->predicates[$i];
            $success = $currentPredicate->evaluate();
            if (!$success) {
                $this->addFailure($currentPredicate);
            }
        }

        return $success;
    }

    /**
     * @return bool
     */
    private function resolveOr(): bool
    {
        $success = false;
        for ($i = 0, $countPredicates = count($this->predicates); !$success && $i < $countPredicates; $i++) {
            $currentPredicate = $this->predicates[$i];
            $success = $currentPredicate->evaluate();
            if (!$success) {
                $this->addFailure($currentPredicate);
            }
        }
        return $success;
    }

    /**
     * @return AbstractPredicate[]
     */
    public function getFailures(): array
    {
        // ? les agrégés sont responsables de l'échec de la résolution d'un agrégat
        return array_reduce(
            $this->failures,
            static function (array $failures, AbstractPredicate $failedPredicate) {
                return array_merge($failures, $failedPredicate->getFailures());
            },
            []
        );
    }
}
