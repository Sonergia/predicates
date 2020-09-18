<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\Aggregate;
use Sonergia\Predicates\IsTruePredicate;
use PHPUnit\Framework\TestCase;

class IsTruePredicateTest extends TestCase
{

    /** @test */
    public function resolve_true()
    {
        $isTrue = new IsTruePredicate();
        $this->assertEquals(true, $isTrue());
        $this->assertTrue($isTrue->isTrue());
        $this->assertFalse($isTrue->isFalse());
    }

    /** @test */
    public function has_no_failure()
    {
        $isTrue = new IsTruePredicate();
        $isTrue();
        $this->assertFalse($isTrue->hasFailures());
    }

    /** @test */
    public function or_returns_aggregate()
    {
        $isTrue = new IsTruePredicate();
        $this->assertInstanceOf(Aggregate::class, $isTrue->or($isTrue));
    }

    /** @test */
    public function and_returns_aggregate()
    {
        $isTrue = new IsTruePredicate();
        $this->assertInstanceOf(Aggregate::class, $isTrue->and($isTrue));
    }

    /** @test */
    public function has_length_of_one()
    {
        $isTrue = new IsTruePredicate(null);
        $this->assertEquals(1, count($isTrue));
    }
}
