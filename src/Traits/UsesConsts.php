<?php declare(strict_types=1);

namespace Baethon\Union\Traits;

trait UsesConsts
{
    private static function toConstName(string $name): string
    {
        $snakeCase = strtoupper(preg_replace('/(.)(?=[A-Z])/u', '$1_', $name));

        return sprintf('%s::%s', get_called_class(), $snakeCase);
    }

    private function getConstants(): array
    {
        $reflection = new \ReflectionClass(get_called_class());
        return $reflection->getConstants();
    }
}
