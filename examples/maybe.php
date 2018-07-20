<?php declare(strict_types=1);

require_once(__DIR__.'/../vendor/autoload.php');

use Baethon\Union\AbstractUnion;

class Maybe extends AbstractUnion
{
    const SOME = 'Some:x';

    const NONE = 'None';

    public function map(callable $fn): Maybe
    {
        return $this->matchWith([
            static::SOME => function ($x) use ($fn) {
                return static::Some($fn($x));
            },
            static::NONE => function () {
                return $this;
            }
        ]);
    }

    public function getOrElse($defaultValue)
    {
        return $this->matchWith([
            static::SOME => function ($x) {
                return $x;
            },
            static::NONE => function () use ($defaultValue) {
                return $defaultValue;
            }
        ]);
    }
}

$plusTen = function (int $i) {
    return $i + 10;
};

$some = Maybe::Some(10);
$none = Maybe::None();

var_dump(
    $some->map($plusTen)
        ->getOrElse(2)
);

var_dump(
    $none->map($plusTen)
        ->getOrElse(0)
);
