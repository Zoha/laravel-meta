<?php


namespace Zoha\Meta\Helpers;

use Zoha\Meta\Helpers\MetaHelper as Meta;

abstract class SetMetaHelper
{
    /**
     * determine types of given meta
     *
     * @param $key
     * @param $value
     * @param $type
     * @return array
     */
    public static function determineMetaTypes($key, $value, $type)
    {
        $types = [];
        if ($type === null && !is_array($key)) {
            $type = Meta::guessType($value);
            if ($type == Meta::META_TYPE_COLLECTION || $type == Meta::META_TYPE_ARRAY || $type == Meta::META_TYPE_JSON) {
                $type = Meta::META_TYPE_COLLECTION;
            }
        } elseif (is_array($key)) {
            foreach ($key as $keyItem => $keyItemValue) {
                if ($value === null) {
                    $thisItemType = Meta::guessType($keyItemValue);
                    if ($thisItemType == Meta::META_TYPE_COLLECTION || $thisItemType == Meta::META_TYPE_ARRAY || $thisItemType == Meta::META_TYPE_JSON) {
                        $thisItemType = Meta::META_TYPE_COLLECTION;
                    }
                    $types[$keyItem] = $thisItemType;
                } else {
                    $types[$keyItem] = $value;
                }
            }
        }
        return [true, $type, $types];
    }

    /**
     * convert collections and collections to json for insert in database
     *
     * @param $key
     * @param $value
     * @param $type
     * @param $types
     * @return array
     */
    public static function convertArrayAndCollectionsMetaToJson($key, $value, $type, $types)
    {
        if (is_array($key)) {
            foreach ($key as $keyItem => $keyItemValue) {
                if ($types[$keyItem] == Meta::META_TYPE_COLLECTION || $types[$keyItem] == Meta::META_TYPE_ARRAY || $types[$keyItem] == Meta::META_TYPE_JSON) {
                    $key[$keyItem] = Meta::convertMetaValueToType($keyItemValue, Meta::META_TYPE_JSON);
                } else {
                    $key[$keyItem] = Meta::convertMetaValueToType($keyItemValue, $types[$keyItem]);
                }
            }
        } else {
            if ($type == Meta::META_TYPE_COLLECTION || $type == Meta::META_TYPE_ARRAY || $type == Meta::META_TYPE_JSON) {
                $value = Meta::convertMetaValueToType($value, Meta::META_TYPE_JSON);
            } else {
                $value = Meta::convertMetaValueToType($value, $type);
            }
        }
        return [true, $key, $value];
    }

    /**
     * check meta already exists or not
     *
     * @param $instance
     * @param $key
     * @return array | bool
     */
    public static function checkMetaAlreadyExistsOrNot($instance, $key)
    {
        $currentMeta = null;
        $currentMetaItem = null;
        if (is_array($key)) {
            $currentMetaItem = [];
            foreach ($key as $keyItem => $keyItemValue) {
                if (!is_string($keyItem) || is_int($keyItem)) {
                    return [false, null, null];
                }
                $currentMetaItem[$keyItem] = $instance->getLoadedMeta()->where('key', $keyItem);
                if (!$currentMetaItem[$keyItem]->count()) { // if meta not founded
                    return [false, null, null];
                }
            }
        } else {
            $currentMeta = $instance->getLoadedMeta()->where('key', $key);
            if (!$currentMeta->count()) { // if meta not founded
                return [false, null, null];
            }
        }
        return [true, $currentMeta, $currentMetaItem];
    }

    /**
     * final step for update single or multiple meta
     * execute update and update values in to database
     *
     * @param $instance
     * @param $key
     * @param $value
     * @param $type
     * @param $types
     * @param $currentMetaItem
     * @param $currentMeta
     */
    public static function executeUpdateMeta(
        $instance,
        $key,
        $value,
        $type,
        $types,
        $currentMetaItem,
        $currentMeta
    ) {
        if (!is_array($key)) {
            $currentMeta = $currentMeta->first();
            $currentMeta->type = $type;
            $currentMeta->value = $value;
            $currentMeta->save();
            $singleUpdatedMeta = $instance->getLoadedMeta()->where('id', $currentMeta->id)->first();
            $singleUpdatedMeta->type = $type;
            $singleUpdatedMeta->value = $value;
            $instance->refreshLoadedMetaItems();
        } else {
            foreach ($key as $keyItem => $keyItemValue) {
                $currentMetaItem[$keyItem] = $currentMetaItem[$keyItem]->first();
                $currentMetaItem[$keyItem]->type = $types[$keyItem];
                $currentMetaItem[$keyItem]->value = $keyItemValue;
                $currentMetaItem[$keyItem]->save();
            }
            foreach ($key as $keyItem => $keyItemValue) {
                $singleUpdatedMeta = $instance->getLoadedMeta()->where('id', $currentMetaItem[$keyItem]->id)->first();
                $singleUpdatedMeta->type = $types[$keyItem];
                $singleUpdatedMeta->value = $keyItemValue;
            }
            $instance->refreshLoadedMetaItems();
        }
    }

    /**
     * final step for create single or multiple meta
     * execute create and insert values in to database
     *
     * @param $instance
     * @param $key
     * @param $value
     * @param $type
     * @param $types
     * @param $currentMetaItem
     * @param $currentMeta
     */
    public static function executeCreateMeta(
        $instance,
        $key,
        $value,
        $type,
        $types
    ) {
        if (!is_array($key)) {
            $newMeta = new \Zoha\Meta\Models\Meta;
            $newMeta->status = true;
            $newMeta->type = $type;
            $newMeta->key = $key;
            $newMeta->value = $value;
            $instance->meta()->save($newMeta);
            $instance->getLoadedMeta()->add($newMeta);
            $instance->refreshLoadedMetaItems();
        } else {
            $currentMetaItem = [];
            foreach ($key as $keyItem => $keyItemValue) {
                $currentMetaItemTemporary = new \Zoha\Meta\Models\Meta;
                $currentMetaItemTemporary->status = true;
                $currentMetaItemTemporary->type = $types[$keyItem];
                $currentMetaItemTemporary->key = $keyItem;
                $currentMetaItemTemporary->value = $keyItemValue;
                $currentMetaItem[] = $currentMetaItemTemporary;
            }
            $instance->meta()->saveMany($currentMetaItem);
            foreach ($currentMetaItem as $singleCreatedMetaItem) {
                $instance->getLoadedMeta()->add($singleCreatedMetaItem);
            }
            $instance->refreshLoadedMetaItems();
        }
    }
}