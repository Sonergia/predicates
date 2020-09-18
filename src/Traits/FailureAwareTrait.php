<?php

namespace Sonergia\Predicates\Traits;

use Sonergia\Predicates\Contracts\FailureAwareContract;

trait FailureAwareTrait
{
    /**
     * @var FailureAwareContract[]
     */
    private $failures = [];

    /**
     * @return array
     */
    abstract public function getFailures(): array;

    /**
     * @return FailureAwareContract
     */
    protected function resetFailures(): FailureAwareContract
    {
        $this->failures = [];
        return $this;
    }

    /**
     * @param FailureAwareContract $failureAware
     * @return FailureAwareContract
     */
    protected function addFailure(FailureAwareContract $failureAware): FailureAwareContract
    {
        $this->failures[] = $failureAware;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFailures(): bool
    {
        return (bool)$this->getFailures();
    }

    /**
     * @return bool
     */
    public function hasSingleFailure(): bool
    {
        return 1 === count($this->getFailures());
    }
}
