<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\IsFalsePredicate;
use PHPUnit\Framework\TestCase;

class IsFalsePredicateTest extends TestCase
{

    /** @test */
    public function resolve_false()
    {
        $isFalse = new IsFalsePredicate();
        $this->assertEquals(false, $isFalse());
        $this->assertTrue($isFalse->isFalse());
        $this->assertFalse($isFalse->isTrue());
    }

    /** @test */
    public function has_single_failure()
    {
        $isFalse = new IsFalsePredicate();
        $isFalse();
        $this->assertTrue($isFalse->hasSingleFailure());
    }

    /** @test */
    public function is_single_failure()
    {
        $isFalse = new IsFalsePredicate();
        $isFalse();
        $this->assertEquals(1, count($isFalse->getFailures()));
        $this->assertEquals($isFalse, $isFalse->getFailures()[0]);
    }

    /** @test */
    public function has_length_of_one()
    {
        $isFalse = new IsFalsePredicate(null);
        $this->assertEquals(1, count($isFalse));
    }
}
