<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\IsFalsePredicate;
use Sonergia\Predicates\IsTruePredicate;
use Sonergia\Predicates\NotPredicate;
use PHPUnit\Framework\TestCase;

class NotPredicateTest extends TestCase
{
    /** @test */
    public function true_predicate_resolve_false()
    {
        $isTrue = new IsTruePredicate();
        $shouldBeFalse = new NotPredicate($isTrue);
        $this->assertEquals(!$isTrue(), $shouldBeFalse());
    }

    /** @test */
    public function false_predicate_resolve_true()
    {
        $isTrue = new IsFalsePredicate();
        $shouldBeFalse = new NotPredicate($isTrue);
        $this->assertEquals(!$isTrue(), $shouldBeFalse());
    }

    /** @test */
    public function has_length_of_one()
    {
        $isFalse = new IsFalsePredicate();
        $shouldBeTrue = new NotPredicate($isFalse);
        $this->assertEquals(1, count($shouldBeTrue));
    }
}
