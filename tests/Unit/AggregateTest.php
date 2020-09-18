<?php

namespace Sonergia\Predicates;

use Sonergia\Predicates\Aggregate;
use Sonergia\Predicates\Exceptions\PredicateException;
use Sonergia\Predicates\Exceptions\UnresolvablePredicateException;
use Sonergia\Predicates\IsFalsePredicate;
use Sonergia\Predicates\IsTruePredicate;
use PHPUnit\Framework\TestCase;

class AggregateTest extends TestCase
{
    /** @test */
    public function cannot_resolve_and_empty_aggregate()
    {
        $predicate = new Aggregate([]);
        $this->expectException(UnresolvablePredicateException::class);
        $predicate->evaluate();
    }

    /** @test */
    public function cannot_resolve_or_empty_aggregate()
    {
        $predicate = new Aggregate([], Aggregate::PREDICATE_JOIN_OR);
        $this->expectException(UnresolvablePredicateException::class);
        $predicate->evaluate();
    }

    /** @test */
    public function can_create_and_aggregate()
    {
        $predicate = new Aggregate([], Aggregate::PREDICATE_JOIN_AND);
        $this->assertInstanceOf(Aggregate::class, $predicate);
        $this->assertTrue($predicate->isJoinedByAnd());
    }

    /** @test */
    public function can_create_or_aggregate()
    {
        $predicate = new Aggregate([], Aggregate::PREDICATE_JOIN_OR);
        $this->assertInstanceOf(Aggregate::class, $predicate);
        $this->assertTrue($predicate->isJoinedByOr());
    }

    /** @test */
    public function cannot_create_with_something_else()
    {
        $this->expectException(PredicateException::class);
        new Aggregate([], 2);
    }

    /** @test */
    public function single_false_and_predicate_resolve_to_false()
    {
        $falsePredicate = new IsFalsePredicate();
        $falseAggregate = new Aggregate([$falsePredicate]);
        $this->assertEquals($falsePredicate(), $falseAggregate());
    }

    /** @test */
    public function single_true_and_predicate_resolve_to_true()
    {
        $truePredicate = new IsTruePredicate();
        $trueAggregate = new Aggregate([$truePredicate]);
        $this->assertEquals($truePredicate(), $trueAggregate());
    }

    /** @test */
    public function single_false_or_predicate_resolve_to_false()
    {
        $falsePredicate = new IsFalsePredicate();
        $falseAggregate = new Aggregate([$falsePredicate], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals($falsePredicate(), $falseAggregate());
    }

    /** @test */
    public function single_true_or_predicate_resolve_to_true()
    {
        $truePredicate = new IsTruePredicate();
        $trueAggregate = new Aggregate([$truePredicate], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals($truePredicate(), $trueAggregate());
    }

    /** @test */
    public function true_and_true_resolve_to_true()
    {
        $shouldBeTrue = new Aggregate([
            new IsTruePredicate(),
            new IsTruePredicate(),
        ]);
        $this->assertEquals(true, $shouldBeTrue());
    }

    /** @test */
    public function true_and_false_resolve_to_false()
    {
        $shouldBeFalse = new Aggregate([
            new IsTruePredicate(),
            new IsFalsePredicate(),
        ]);
        $this->assertEquals(false, $shouldBeFalse());
    }

    /** @test */
    public function false_and_true_resolve_to_false()
    {
        $shouldBeFalse = new Aggregate([
            new IsFalsePredicate(),
            new IsTruePredicate(),
        ]);
        $this->assertEquals(false, $shouldBeFalse());
    }

    /** @test */
    public function false_and_false_resolve_to_false()
    {
        $shouldBeFalse = new Aggregate([
            new IsFalsePredicate(),
            new IsFalsePredicate(),
        ]);
        $this->assertEquals(false, $shouldBeFalse());
    }

    /** @test */
    public function true_or_true_resolve_to_true()
    {
        $shouldBeTrue = new Aggregate([
            new IsTruePredicate(),
            new IsTruePredicate(),
        ], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals(true, $shouldBeTrue());
    }

    /** @test */
    public function true_or_false_resolve_to_true()
    {
        $shouldBeTrue = new Aggregate([
            new IsTruePredicate(),
            new IsFalsePredicate(),
        ], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals(true, $shouldBeTrue());
    }

    /** @test */
    public function false_or_true_resolve_to_true()
    {
        $shouldBeTrue = new Aggregate([
            new IsFalsePredicate(),
            new IsTruePredicate(),
        ], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals(true, $shouldBeTrue());
    }

    /** @test */
    public function false_or_false_resolve_to_false()
    {
        $shouldBeFalse = new Aggregate([
            new IsTruePredicate(),
            new IsTruePredicate(),
        ], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals(true, $shouldBeFalse());
    }

    /** @test */
    public function compose_and_returns_same_result()
    {
        $map = [
            [true, true],
            [true, false],
            [false, false],
            [false, true]
        ];
        foreach ($map as $values) {
            $aggregate = new Aggregate(
                array_map(static function ($value) {
                    return $value ? new IsTruePredicate() : new IsFalsePredicate();
                }, $values)
            );
            $composition = array_reduce($values, function ($carry, $value) {
                $predicate = $value ? new IsTruePredicate() : new IsFalsePredicate();
                return null === $carry ? $predicate : $carry->and($predicate);
            }, null);
            $this->assertEquals(
                $aggregate(),
                $composition()
            );
        }
    }

    /** @test */
    public function compose_or_returns_same_result()
    {
        $map = [
            [true, true],
            [true, false],
            [false, false],
            [false, true]
        ];
        foreach ($map as $values) {
            $aggregate = new Aggregate(
                array_map(static function ($value) {
                    return $value ? new IsTruePredicate() : new IsFalsePredicate();
                }, $values),
                Aggregate::PREDICATE_JOIN_OR
            );
            $composition = array_reduce($values, function ($carry, $value) {
                $predicate = $value ? new IsTruePredicate() : new IsFalsePredicate();
                return null === $carry ? $predicate : $carry->or($predicate);
            }, null);
            $this->assertEquals(
                $aggregate(),
                $composition()
            );
        }
    }

    // ? FAILURES

    /** @test */
    public function has_single_failure_if_first_predicate_fails_in_and()
    {
        $predicate = new Aggregate([
            new IsFalsePredicate(),
            new IsFalsePredicate(),
            new IsFalsePredicate(),
            new IsFalsePredicate(),
        ]);
        $predicate();
        $this->assertTrue($predicate->hasSingleFailure());
    }

    /** @test */
    public function has_failures_count_equals_predicate_count_with_only_failed_or()
    {
        $predicate = new Aggregate([
            new IsFalsePredicate(),
            new IsFalsePredicate(),
            new IsFalsePredicate(),
            new IsFalsePredicate(),
        ], Aggregate::PREDICATE_JOIN_OR);
        $predicate();
        $this->assertEquals(4, count($predicate->getFailures()));
    }

    /** @test */
    public function aggregate_and_with_single_false_predicate_resolve_to_false()
    {
        $aggregate = new Aggregate([
            new IsFalsePredicate(),
        ]);
        $this->assertEquals(false, $aggregate());
    }

    /** @test */
    public function aggregate_or_with_single_false_predicate_resolve_to_false()
    {
        $aggregate = new Aggregate([
            new IsFalsePredicate(),
        ], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals(false, $aggregate());
    }

    /** @test */
    public function aggregate_and_with_single_true_predicate_resolve_to_true()
    {
        $aggregate = new Aggregate([
            new IsTruePredicate(),
        ]);
        $this->assertEquals(true, $aggregate());
    }

    /** @test */
    public function aggregate_or_with_single_true_predicate_resolve_to_true()
    {
        $aggregate = new Aggregate([
            new IsTruePredicate(),
        ], Aggregate::PREDICATE_JOIN_OR);
        $this->assertEquals(true, $aggregate());
    }
}
