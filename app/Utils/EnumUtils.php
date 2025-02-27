<?php

namespace App\Utils;

class EnumUtils
{
    /**
     * @return array<int, string>
     */
    public static function getEnumOptions(string $enumClass, string $method): array
    {
        $reflection = new \ReflectionClass($enumClass);
        $constants = $reflection->getConstants();

        $options = [];
        foreach ($constants as $key => $value) {
            $options[$value] = $enumClass::$method($value);
        }

        return $options;
    }
}
