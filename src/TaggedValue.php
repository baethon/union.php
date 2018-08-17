<?php declare(strict_types=1);

namespace Baethon\Union;

final class TaggedValue
{
    /**
     * @var TagSignature
     */
    private $signature;

    /**
     * @var array
     */
    private $arguments;

    private function __construct(TagSignature $signature, array $arguments = [])
    {
        $this->validateArguments($signature, $arguments);

        $this->signature = $signature;
        $this->arguments = $arguments;
    }

    public static function of(TagSignature $signature, array $arguments = []): self
    {
        return new static($signature, $arguments);
    }

    public function hasType(TagSignature $signature): bool
    {
        return $this->signature->sameType($signature);
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    private function validateArguments(TagSignature $signature, array $arguments)
    {
        $argumentsLength = count($arguments);
        $parametersLength = count($signature->getParameters());

        if ($argumentsLength !== $parametersLength) {
            throw new \LengthException(
                "Tag {$signature->getType()} requires {$parametersLength} arguments, {$argumentsLength} provided."
            );
        }
    }
}
