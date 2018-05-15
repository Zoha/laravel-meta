<?php


namespace Zoha\Meta\Contracts;


interface MetaCreator
{
    /**
     * create new meta actions . return false if meta with this features already exists
     * note : $key value can not be array in this method
     *
     * @param $instance
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @return bool
     */
    public static function createMeta($instance, $key, $value, $type);
}