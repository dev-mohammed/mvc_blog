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