<?php


namespace Zoha\Meta\Helpers;

use Illuminate\Database\Eloquent\Collection;

class MetaHelper
{

    //----------------------------------------- Properties ------------------------------------------//

    const NO_VALUE_FOR_PARAMETER = "NOVALUEFORPARAMETER";
    const META_TYPE_STRING = "string";
    const META_TYPE_COLLECTION = "collection";
    const META_TYPE_JSON = "json";
    const META_TYPE_ARRAY = "array";
    const META_TYPE_BOOLEAN = "boolean";
    const META_TYPE_INTEGER = "integer";
    const META_TYPE_FLOAT = "float";
    const META_TYPE_NULL = "null";

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * process and return appropriate type meta value by given collection of meta
     *
     * @param Collection $collection <p>collection of returned meta from db</p>
     * @param string $key
     * @param string $type
     * @return mixed
     */
    public static function returnValue(Collection $collection, $key, $type = null)
    {
        $databaseResult = $collection->where('key', $key);
        if ($databaseResult->count()) {
            $databaseResult = $databaseResult->first();
        } else {
            return null;
        }
        if ($type !== null) {
            return static::convertMetaValueToType($databaseResult->value, $type);
        }
        if ($databaseResult->type == null) {
            $databaseResult->type = static::guessType($databaseResult->value);
        }
        return static::convertMetaValueToType($databaseResult->value, $databaseResult->type);
    }

    /**
     * guess type of given meta value
     *
     * @param mixed $value <p>value to guess</p>
     * @return string
     */
    public static function guessType($value)
    {
        if ($value === null) {
            return MetaHelper::META_TYPE_NULL;
        }
        if (is_array($value)) {
            return MetaHelper::META_TYPE_ARRAY;
        }
        if (
            is_string($value) &&
            !is_object(json_decode($value)) &&
            !is_array(json_decode($value)) &&
            !preg_match('/^[0-9\.]+$/', $value)) {
            return MetaHelper::META_TYPE_STRING;
        }
        if (is_string($value) && (is_object(json_decode($value)) || is_array(json_decode($value)))) {
            return MetaHelper::META_TYPE_JSON;
        }
        if ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection) {
            return MetaHelper::META_TYPE_COLLECTION;
        }
        if (($value === true || $value === false) && $value !== 1 && $value !== 0) {
            return MetaHelper::META_TYPE_BOOLEAN;
        }
        if (preg_match('/^[0-9]+\.[0-9]+$/', $value)) {
            return MetaHelper::META_TYPE_FLOAT;
        }
        if (preg_match('/^\d+$/', $value)) {
            return MetaHelper::META_TYPE_INTEGER;
        }
        return MetaHelper::META_TYPE_STRING;
    }

    /**
     * convert given value to proper type by guessing or custom type
     *
     * @param mixed $value
     * @param string $type <p> custom value type : should be a const in meta class </p>
     * @return mixed
     */
    public static function convertMetaValueToType($value, $type = null)
    {
        $finalType = MetaHelper::guessType($value);
        if ($type !== null) {
            $finalType = $type;
        }
        switch ($finalType) {
            case MetaHelper::META_TYPE_STRING :
                $value = self::convertToStringType($value);
                break;
            case MetaHelper::META_TYPE_INTEGER:
                $value = self::convertToIntegerType($value);
                break;
            case MetaHelper::META_TYPE_FLOAT:
                $value = self::convertToFloatType($value);
                break;
            case MetaHelper::META_TYPE_COLLECTION :
                $value = self::convertToCollectionType($value);
                break;
            case MetaHelper::META_TYPE_JSON:
                $value = self::convertToJsonType($value);
                break;
            case MetaHelper::META_TYPE_ARRAY:
                $value = self::convertToArrayType($value);
                break;
            case MetaHelper::META_TYPE_BOOLEAN :
                $value = self::convertToBooleanType($value);
                break;
            case MetaHelper::META_TYPE_NULL :
                $value = null;
                break;
        }
        return $value;
    }

    /**
     * return true if value is empty for different meta type
     *
     * @param mixed $value
     * @return bool
     */
    public static function isNullValue($value)
    {
        return (
            $value !== 0 && (
                $value === [] ||
                $value === null ||
                $value === collect([]) ||
                empty($value) ||
                $value === '{}' ||
                $value === '[]')
        );
    }

    /**
     * convert given value to json if is collection or array
     *
     * @param mixed $value
     * @return mixed
     */
    public static function convertMetaValueForSearch($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        } elseif ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection) {
            return $value->toJson();
        } elseif (is_bool($value)) {
            return ($value == true) ? '1' : '0';
        }
        return $value;
    }

    /**
     * convert given value to string
     *
     * @param $value
     * @return string
     */
    protected static function convertToStringType($value): string
    {
        if (is_array($value)) {
            $value = json_encode($value);
        } elseif ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection) {
            $value = $value->toJson();
        } elseif (is_bool($value)) {
            $value = $value == true ? 'true' : 'false';
        }
        $value = (string)$value;
        return $value;
    }

    /**
     * convert given value to integer
     *
     * @param $value
     * @return float|int
     */
    protected static function convertToIntegerType($value)
    {
        if ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection || is_array($value)) {
            $value = 0;
        }
        return (int)$value;
    }

    /**
     * convert given value to integer
     *
     * @param $value
     * @return float|int
     */
    protected static function convertToFloatType($value)
    {
        if ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection || is_array($value)) {
            $value = 0;
        }
        return (float)$value;
    }

    /**
     * convert given value to collection
     *
     * @param $value
     * @return Collection|\Illuminate\Support\Collection|string
     */
    protected static function convertToCollectionType($value)
    {
        if (is_string($value) && (is_object(json_decode($value)) || is_array(json_decode($value)))) {
            $value = collect(json_decode($value, true));
        } elseif (is_array($value)) {
            $value = collect($value);
        } else {
            $value = (
                $value instanceof Collection || $value instanceof \Illuminate\Support\Collection
            ) ? $value : collect([]);
        }
        return $value;
    }

    /**
     * convert given value to json
     *
     * @param $value
     * @return string
     */
    protected static function convertToJsonType($value): string
    {
        if ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection) {
            $value = $value->toJson();
        } elseif (is_array($value)) {
            $value = json_encode($value);
        } else {
            $value = is_string($value) && (is_object(json_decode($value)) || is_array(json_decode($value))) ? $value : '{}';
        }
        return $value;
    }

    /**
     * convert given value to array
     *
     * @param $value
     * @return array|mixed|string
     */
    protected static function convertToArrayType($value)
    {
        if ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection) {
            $value = $value->toArray();
        } elseif (is_string($value) && (is_object(json_decode($value)) || is_array(json_decode($value)))) {
            $value = json_decode($value, true);
        } else {
            $value = is_array($value) ? $value : [];
        }
        return $value;
    }

    /**
     * convert given value to boolean
     *
     * @param $value
     * @return bool
     */
    protected static function convertToBooleanType($value): bool
    {
        if ($value instanceof Collection || $value instanceof \Illuminate\Support\Collection) {
            $value = true;
        } elseif (is_string($value)) {
            if ($value === 'true') {
                $value = true;
            }
            if ($value === 'false') {
                $value = false;
            }
        }
        $value = (boolean)$value;
        return $value;
    }
}
