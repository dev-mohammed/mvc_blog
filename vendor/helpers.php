<?php

if (!function_exists('pre')) {
    /**
     *Visualize the given variable in browser
     * @param mixed $var
     * @return void
     */
    function pre($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if (!function_exists('array_get')) {
    /**
     * Get the value from the given array for the given key if found
     * Otherwise get the default value
     *
     * @param array $array
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}


if (!function_exists('_e')) {
    /**
     * Escape the given value
     * @param string $value
     * @return string
     */
    function _e($value)
    {
        return htmlspecialchars($value);
    }
}




