<?php

namespace Spatie\HtmlElement\Helpers;

class Arr
{
    /**
     * @param array $array
     * @return array
     */
    public static function flatten(array $array)
    {
        $flattened = [];

        foreach ($array as $element) {
            $flattened = array_merge(
                $flattened,
                is_array($element) ? static::flatten($element) : [$element]
            );
        }

        return $flattened;
    }

    /**
     * @param array $array
     * @param callable $mapper
     * @return array
     */
    public static function map(array $array, callable $mapper)
    {
        return array_map($mapper, $array);
    }

    /**
     * @param array $array
     * @param callable $mapper
     * @return array
     */
    public static function flatMap(array $array, callable $mapper)
    {
        return static::flatten(static::map($array, $mapper));
    }
}
