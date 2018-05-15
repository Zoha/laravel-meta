<?php


namespace Zoha\Meta\Contracts;


interface MetaUpdator
{
    /**
     * updating an existing meta actions
     *
     * @param $instance
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @return bool : true on success false if meta not founded
     */
    public static function updateMeta($instance, $key, $value, $type);
}