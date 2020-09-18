<?php

namespace Sonergia\Predicates\Contracts;

interface FailureAwareContract
{
    /**
     * @return self[]
     */
    public function getFailures(): array;

    /**
     * @return bool
     */
    public function hasFailures(): bool;

    /**
     * @return bool
     */
    public function hasSingleFailure(): bool;
}
