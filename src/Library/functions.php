<?php


use Htk\Base\Exception\ClassNotFoundException;
use Htk\Base\Library\Container;

if (! function_exists('make')) {
    /**
     * Create a object instance by container
     * @throws ClassNotFoundException
     */
    function make(string $name, array $parameters = [])
    {
        $parameters = array_values($parameters);
        return Container::make($name, $parameters);
    }
}