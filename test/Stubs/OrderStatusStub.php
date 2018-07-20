<?php

namespace Test\Stubs;

class OrderStatusStub extends \Baethon\Union\AbstractUnion
{
    const NOT_PAID = 'Not paid';

    const PAID = 'Paid';

    const CANCELLED = 'Cancelled';
}
