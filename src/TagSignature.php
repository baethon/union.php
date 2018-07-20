<?php declare(strict_types=1);

namespace Baethon\Union;

/**
 * Represents single tag signature.
 *
 * A signature is a string containing type and tags parameters.
 * It's created in simple format: $Type:param1,param2,...,paramN
 */
final class TagSignature
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $parameters;

    private function __construct(string $type, array $parameters = [])
    {
        $this->type = $type;
        $this->parameters = $parameters;
    }

    public static function fromString(string $signature): self
    {
        if (false !== strpos($signature, ':')) {
            [$type, $parameters] = explode(':', $signature);
            $parameters = explode(',', $parameters);

            return new static($type, $parameters);
        }

        return new static($signature, []);
    }

    public function sameType(TagSignature $other): bool
    {
        return $this->type === $other->type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
