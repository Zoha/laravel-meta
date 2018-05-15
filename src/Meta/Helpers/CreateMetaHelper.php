<?php


namespace Zoha\Meta\Helpers;

use Zoha\Meta\Contracts\MetaCreator;

class CreateMetaHelper extends SetMetaHelper implements MetaCreator
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
    public static function createMeta($instance, $key, $value, $type)
    {
        if (!is_string($key) && !is_array($key)) {
            return false;
        }
        // check if meta already exists
        $success = static::checkMetaAlreadyExistsOrNot($instance, $key);
        if (!$success) {
            return false;
        }

        //determine types
        list($success, $type, $types) = static::determineMetaTypes($key, $value, $type);
        if (!$success) {
            return false;
        }

        //convert arrays and json's to collection
        list($success, $key, $value) = static::convertArrayAndCollectionsMetaToJson($key, $value, $type, $types);
        if (!$success) {
            return false;
        }

        static::executeCreateMeta($instance, $key, $value, $type, $types);
        return true;
    }

    /**
     * @override
     * check meta already exists or not
     *
     * @param $instance
     * @param $key
     * @return array | bool
     */
    public static function checkMetaAlreadyExistsOrNot($instance, $key)
    {
        if (is_array($key)) {
            $currentMetaItem = [];
            foreach ($key as $keyItem => $keyItemValue) {
                if (!is_string($keyItem) || is_int($keyItem)) {
                    return false;
                }
                $currentMetaItem[$keyItem] = $instance->getLoadedMeta()->where('key', $keyItem);
                if ($currentMetaItem[$keyItem]->count()) { // if meta not founded
                    return false;
                }
            }
        } else {
            $currentMeta = $instance->getLoadedMeta()->where('key', $key);
            if ($currentMeta->count()) { // if meta not founded
                return false;
            }
        }
        return true;
    }
}