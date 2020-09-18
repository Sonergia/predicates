<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\IsNullPredicate;
use PHPUnit\Framework\TestCase;

class IsNullPredicateTest extends TestCase
{
    /** @test */
    public function returns_true_with_null_argument()
    {
        $isNull = new IsNullPredicate(null);
        $this->assertEquals(true, $isNull());
    }

    /** @test */
    public function returns_false_with_not_null_argument()
    {
        $isNull = new IsNullPredicate('something');
        $this->assertEquals(false, $isNull());
    }

    /** @test */
    public function has_length_of_one()
    {
        $isNotNull = new IsNullPredicate(null);
        $this->assertEquals(1, count($isNotNull));
    }
}
