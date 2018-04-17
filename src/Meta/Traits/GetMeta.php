<?php


namespace Zoha\Meta\Traits;

use Zoha\Meta\Helpers\MetaHelper as Meta;

trait GetMeta
{
    /**
     * get meta value by given key
     *
     * @param string $key
     * @param mixed $defaultValue
     * @param string $customType
     * @return mixed
     */
    public function getMeta($key, $defaultValue = Meta::NO_VALUE_FOR_PARAMETER, $customType = null)
    {
        if (!is_string($key)) {
            return $defaultValue;
        }
        $metaResult = Meta::returnValue($this->getloadedMeta(), $key, $customType);
        if (Meta::isNullValue($metaResult)) {
            if ($defaultValue !== Meta::NO_VALUE_FOR_PARAMETER) {
                return $defaultValue;
            }
            return $metaResult;
        }

        return $metaResult;
    }
}