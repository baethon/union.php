<?php

namespace Test;

use Baethon\Union\TaggedValue;
use Baethon\Union\TagSignature;

class TaggedValueTest extends \PHPUnit\Framework\TestCase
{
    private $signature;

    protected function setUp(): void
    {
        $this->signature = TagSignature::fromString('Some:x');
    }

    public function test_it_can_be_created()
    {
        $tag = TaggedValue::of($this->signature, [1]);

        $this->assertEquals([1], $tag->getArguments());
        $this->assertTrue($tag->hasType($this->signature));
    }

    /**
     * @dataProvider invalidArgumentsProvider
     */
    public function test_it_validates_length_of_arguments(TagSignature $signature, array $arguments)
    {
        $this->expectException(\LengthException::class);

        TaggedValue::of($signature, $arguments);
    }

    public function invalidArgumentsProvider()
    {
        return [
            [TagSignature::fromString('None'), [1]],
            [TagSignature::fromString('Stark:name,latname'), ['Jon']],
        ];
    }
}
