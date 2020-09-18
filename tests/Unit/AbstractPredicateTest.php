<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\AbstractPredicate;
use Sonergia\Predicates\Aggregate;
use Sonergia\Predicates\NotPredicate;
use PHPUnit\Framework\TestCase;

class AbstractPredicateTest extends TestCase
{
    /** @test */
    public function has_array_of_failures()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockBuilder(AbstractPredicate::class)
            ->getMock();
        $this->assertIsArray($stub->getFailures());
    }

    /** @test */
    public function has_no_failures_at_instantiation()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $this->assertFalse($stub->hasFailures());
    }

    /** @test */
    public function resolve_false()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $this->assertFalse($stub->evaluate());
        $this->assertFalse($stub->isTrue());
        $this->assertTrue($stub->isFalse());
    }

    /** @test */
    public function has_single_failure()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $stub->evaluate();
        $this->assertTrue($stub->hasSingleFailure());
    }

    /** @test */
    public function is_single_failure()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $stub->evaluate();
        $this->assertSame($stub, $stub->getFailures()[0]);
    }

    /** @test */
    public function or_returns_aggregate()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $this->assertInstanceOf(Aggregate::class, $stub->or($stub));
    }

    /** @test */
    public function and_returns_aggregate()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $this->assertInstanceOf(Aggregate::class, $stub->and($stub));
    }

    /** @test */
    public function me_and_not_me_is_false()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $predicate = $stub->and(new NotPredicate($stub));
        $this->assertEquals(false, $predicate());
    }

    /** @test */
    public function me_or_not_me_is_true()
    {
        /** @var AbstractPredicate $stub */
        $stub = $this->getMockForAbstractClass(AbstractPredicate::class);
        $predicate = $stub->or(new NotPredicate($stub));
        $this->assertEquals(true, $predicate());
    }
}
