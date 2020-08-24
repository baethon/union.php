<?php

namespace Test;

use Baethon\Union\TagSignature;

class TagSignatureTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider signatureProvider
     */
    public function test_it_can_be_created_from_signature(
        string $signature,
        string $expectedType,
        array $expectedParameters) {
        $tag = TagSignature::fromString($signature);

        $this->assertSame($expectedType, $tag->getType());
        $this->assertEquals($expectedParameters, $tag->getParameters());
    }

    public function signatureProvider()
    {
        return [
            ['None', 'None', []],
            ['Some:x', 'Some', ['x']],
            ['Other:x,y', 'Other', ['x', 'y']],
        ];
    }

    public function test_it_can_compare_types()
    {
        $some = TagSignature::fromString('Some:x');
        $none = TagSignature::fromString('None');
        $otherSome = TagSignature::fromString('Some:x');

        $this->assertTrue($some->sameType($otherSome));
        $this->assertFalse($some->sameType($none));
    }
}
