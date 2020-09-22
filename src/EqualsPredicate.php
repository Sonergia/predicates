<?php


namespace Sonergia\Predicates;


class EqualsPredicate extends AbstractPredicate
{
    /** @var mixed */
    protected $value;

    /**
     * @var bool|mixed
     */
    protected $strict = true;

    /** @var mixed */
    protected $expected;

    public function __construct($value, $strict = true)
    {
        $this->value = $value;
        $this->strict = $strict;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * @param $value
     * @return $this
     */
    public function expects($value): self
    {
        $this->expected = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function resolve(): bool
    {
        return $this->strict ? $this->value === $this->expected : $this->value == $this->expected;
    }
}