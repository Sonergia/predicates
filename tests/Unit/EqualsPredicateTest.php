<?php

namespace Sonergia\Predicates;

use PHPUnit\Framework\TestCase;

class EqualsPredicateTest extends TestCase
{
    /** @test */
    public function has_length_of_one()
    {
        $predicate = new EqualsPredicate(null);
        $this->assertEquals(1, count($predicate));
    }

    /** @test */
    public function false_without_expectation()
    {
        $val = "value";
        $shouldBeFalse = new EqualsPredicate($val);
        $this->assertFalse($shouldBeFalse());
    }

    /** @test */
    public function true_when_same()
    {
        $val = "value";
        $shouldBeTrue = (new EqualsPredicate($val))->expects($val);
        $this->assertTrue($shouldBeTrue());
    }

    /** @test */
    public function false_when_different()
    {
        $val = "value";
        $shouldBeFalse = (new EqualsPredicate($val))->expects(false);
        $this->assertFalse($shouldBeFalse());
    }
}
