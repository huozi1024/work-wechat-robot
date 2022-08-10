<?php

/**
 * Get an item from an array using "dot" notation.
 *
 * @param array $array
 * @param string $key
 * @param mixed $default
 */
function arr_get($array, $key, $default = null)
{
    if (array_key_exists($key, $array)) {
        return $array[$key];
    }

    if (strpos($key, '.') === false) {
        return $array[$key] ?? $default;
    }

    foreach (explode('.', $key) as $segment) {
        if (array_key_exists($segment, $array)) {
            $array = $array[$segment];
        } else {
            return $default;
        }
    }
    return $array;
}