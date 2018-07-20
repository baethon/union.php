<?php declare(strict_types=1);

namespace Baethon\Union;

abstract class AbstractUnion
{
    use Traits\UsesConsts;

    /**
     * @var TaggedValue
     */
    private $taggedValue;

    final private function __construct(TaggedValue $taggedValue)
    {
        $this->taggedValue = $taggedValue;
    }

    public static function __callStatic(string $name, array $arguments): self
    {
        return static::of(
            static::getStringSignature($name),
            $arguments
        );
    }

    public function __call(string $name, array $arguments = [])
    {
        if (true === (bool) preg_match('/^is.+/', $name)) {
            return $this->isType($name);
        }

        throw new \BadMethodCallException("Unknown method: {$name}");
    }

    public function matchWith(array $mapping)
    {
        $arguments = $this->taggedValue->getArguments();

        foreach ($mapping as $signature => $callback) {
            if (false === $this->taggedValue->hasType(TagSignature::fromString($signature))) {
                continue;
            }

            return $callback(...$arguments);
        }

        if (true === array_key_exists('*', $mapping)) {
            return $mapping['*'](...$arguments);
        }
    }

    protected static function of(string $signature, array $arguments)
    {
        $signature = TagSignature::fromString($signature);

        return new static(
            TaggedValue::of($signature, $arguments)
        );
    }

    private function isType(string $name): bool
    {
        $signature = TagSignature::fromString(
            static::getStringSignature(preg_replace('/^is/', '', $name))
        );

        return $this->taggedValue->hasType($signature);
    }

    private static function getStringSignature(string $name): string
    {
        return constant(
            static::toConstName($name)
        );
    }
}
