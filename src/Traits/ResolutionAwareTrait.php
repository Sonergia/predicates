<?php

namespace Sonergia\Predicates\Traits;

trait ResolutionAwareTrait
{
    use FailureAwareTrait;

    /**
     * @var null|bool
     */
    private $resolvedValue = null;

    /**
     * @param bool $resolvedValue
     * @return $this
     */
    private function setResolvedValue(bool $resolvedValue): self
    {
        $this->resolvedValue = $resolvedValue;
        return $this;
    }

    /**
     * @return bool|null
     */
    private function getResolvedValue(): ?bool
    {
        return $this->resolvedValue;
    }

    /**
     * @return bool
     */
    public function hasBeenResolved(): bool
    {
        return null !== $this->getResolvedValue();
    }

    /**
     * @return bool
     */
    public function isTrue(): bool
    {
        return true === $this->getResolvedValue();
    }

    /**
     * @return bool
     */
    public function isFalse(): bool
    {
        return false === $this->getResolvedValue();
    }
}
