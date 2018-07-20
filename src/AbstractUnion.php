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

        // apparently PHP is not willing to use __callStatic when
        // a static call is done from objects context
        //
        // this way we need to "emulate" this behaviour
        //
        // @see https://donatstudios.com/PHP-Static-Nonsense
        return $this->__callStatic($name, $arguments);
    }

    final public function matchWith(array $mapping)
    {
        $this->validateTagsMapping($mapping);

        $arguments = $this->taggedValue->getArguments();

        foreach ($mapping as $signature => $callback) {
            if (false === $this->taggedValue->hasType(TagSignature::fromString($signature))) {
                continue;
            }

            return $callback(...$arguments);
        }

        // validateTagsMapping() should take care of checking if there's a wildcard handler
        // we can simply call it
        return $mapping['*'](...$arguments);
    }

    /**
     * Checks if provided mapping covers all defined tags
     *
     * @param array $mapping
     * @return void
     * @throw \UnderflowException when not all tags are covered by mapping
     * @throw \OverflowException when there are more than defined tags covered by mapping
     */
    private function validateTagsMapping(array $mapping)
    {
        $castToType = function (string $signature) {
            return TagSignature::fromString($signature)->getType();
        };

        $hasWildcard = array_key_exists('*', $mapping);
        unset($mapping['*']);

        $definedConsts = array_map($castToType, array_values($this->getConstants()));
        $coveredTypes = array_map($castToType, array_keys($mapping));

        if ([] !== array_diff($coveredTypes, $definedConsts)) {
            throw new \OverflowException('Possibly non-existing tags are mapped.');
        }

        if (false === $hasWildcard && [] !== array_diff($definedConsts, $coveredTypes)) {
            throw new \UnderflowException('Not all tags are covered.');
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
