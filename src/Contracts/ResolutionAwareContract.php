<?php


namespace Sonergia\Predicates\Contracts;


interface ResolutionAwareContract
{
    /**
     * @return bool
     */
    public function hasBeenResolved(): bool;

}