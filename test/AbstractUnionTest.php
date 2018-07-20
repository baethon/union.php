<?php

namespace Test;

use Test\Stubs\MaybeStub;
use Test\Stubs\OrderStatusStub;
use Baethon\Union\AbstractUnion;

class AbstractUnionTest extends \PHPUnit\Framework\TestCase
{
    public function test_it_can_be_created()
    {
        $this->assertInstanceOf(MaybeStub::class, MaybeStub::Some('1'));
        $this->assertInstanceOf(MaybeStub::class, MaybeStub::None());
    }

    public  function test_it_can_be_created_using_complex_names()
    {
        $this->assertInstanceOf(OrderStatusStub::class, OrderStatusStub::notPaid());
    }

    public function test_it_can_be_compared_to_other_type()
    {
        $maybe = MaybeStub::Some('1');

        $this->assertTrue($maybe->isSome());
        $this->assertFalse($maybe->isNone());
    }

    public function test_it_can_be_matched_by_type()
    {
        $maybe = MaybeStub::Some(1);
        $value = $maybe->matchWith([
            MaybeStub::SOME => function (int $no) {
                return $no + 1;
            },
            MaybeStub::NONE => function () {
                return 'oh noes';
            }
        ]);

        $this->assertEquals(2, $value);
    }

    public function test_match_with_can_be_used_with_string_types()
    {
        $maybe = MaybeStub::Some(1);
        $value = $maybe->matchWith([
            'Some' => function (int $no) {
                return $no + 1;
            },
            'None' => function () {
                return 'oh noes';
            }
        ]);

        $this->assertEquals(2, $value);
    }

    public function test_match_with_can_use_wildcard_type()
    {
        $maybe = MaybeStub::Some(1);
        $value = $maybe->matchWith([
            '*' => function () {
                return 2;
            }
        ]);

        $this->assertEquals(2, $value);
    }
}
