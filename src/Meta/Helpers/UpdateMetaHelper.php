<?php


namespace Zoha\Meta\Helpers;

use Zoha\Meta\Contracts\MetaUpdator;
use Zoha\Meta\Helpers\MetaHelper as Meta;

class UpdateMetaHelper extends SetMetaHelper implements MetaUpdator
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
    public static function updateMeta($instance, $key, $value, $type)
    {
        if (!is_string($key) && !is_array($key)) {
            return false;
        }

        // check if meta already exists
        list($success, $currentMeta, $currentMetaItem) = static::checkMetaAlreadyExistsOrNot($instance, $key);
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
        static::executeUpdateMeta($instance, $key, $value, $type, $types, $currentMetaItem, $currentMeta);
        return true;
    }
}