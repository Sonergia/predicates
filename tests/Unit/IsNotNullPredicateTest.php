<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\IsNotNullPredicate;
use PHPUnit\Framework\TestCase;

class IsNotNullPredicateTest extends TestCase
{
    /** @test */
    public function returns_true_with_not_null_argument()
    {
        $isNotNull = new IsNotNullPredicate('something');
        $this->assertEquals(true, $isNotNull());
    }

    /** @test */
    public function returns_false_with_null_argument()
    {
        $isNotNull = new IsNotNullPredicate(null);
        $this->assertEquals(false, $isNotNull());
    }

    /** @test */
    public function has_length_of_one()
    {
        $isNotNull = new IsNotNullPredicate(null);
        $this->assertEquals(1, count($isNotNull));
    }
}
