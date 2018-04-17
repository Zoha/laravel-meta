<?php


namespace Zoha\Meta\Traits;

use Zoha\Meta\Helpers\MetaHelper as Meta;

trait SetMeta
{

    /**
     * add new meta or update an existing one
     *
     * @example : setMeta([
     *      'test1' => 'testvalue1',
     *      'test2' => 'testvalue2',
     *      'test3' => 'testvalue3'
     *  ]);
     * @example : setMeta('test1','value1')
     *
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @param null|bool $createMeta
     * @return bool : true
     */
    public function setMeta($key, $value = null, $type = null, $createMeta = null)
    {
        if ($createMeta !== null) {
            if ($createMeta === false) {
                return $this->updatingMetaAction($key, $value, $type);
            } elseif ($createMeta === true) {
                return $this->creatingMetaAction($key, $value, $type);
            }
        }
        if (is_array($key)) {
            $results = [];
            foreach ($key as $keyItem => $valueItem) {
                $results[] = $this->setMeta($keyItem, $valueItem, $value);
            }
            foreach ($results as $result) {
                if ($result === false) {
                    return false;
                }
            }
            return true;
        }
        $currentMeta = $this->getLoadedMeta()->where('key', $key);
        if ($currentMeta->count()) { // if a meta with this key already exists
            return $this->updatingMetaAction($key, $value, $type);
        } else {
            return $this->creatingMetaAction($key, $value, $type);
        }
    }

    /**
     * updating an existing meta actions
     *
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @return bool : true on success false if meta not founded
     */
    private function updatingMetaAction($key, $value, $type)
    {
        if (!is_string($key) && !is_array($key)) {
            return false;
        }

        $keyIsArray = false;

        // check if meta already exists
        if (is_array($key)) {
            $keyIsArray = true;
            $currentMetaItem = [];
            foreach ($key as $keyItem => $keyItemValue) {
                if (!is_string($keyItem) || is_int($keyItem)) {
                    return false;
                }
                $currentMetaItem[$keyItem] = $this->getLoadedMeta()->where('key', $keyItem);
                if (!$currentMetaItem[$keyItem]->count()) { // if meta not founded
                    return false;
                }
            }
        } else {
            $currentMeta = $this->getLoadedMeta()->where('key', $key);
            if (!$currentMeta->count()) { // if meta not founded
                return false;
            }
        }

        //determine types
        $types = [];
        if ($type === null && $keyIsArray == false) {
            $type = Meta::guessType($value);
            if ($type == Meta::META_TYPE_COLLECTION || $type == Meta::META_TYPE_ARRAY || $type == Meta::META_TYPE_JSON) {
                $type = Meta::META_TYPE_COLLECTION;
            }
        } elseif ($keyIsArray == true) {
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

        //convert arrays and json's to collection
        if ($keyIsArray == true) {
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

        if ($keyIsArray == false) {
            $currentMeta = $currentMeta->first();
            $currentMeta->type = $type;
            $currentMeta->value = $value;
            $currentMeta->save();
            $singleUpdatedMeta = $this->getLoadedMeta()->where('id', $currentMeta->id)->first();
            $singleUpdatedMeta->type = $type;
            $singleUpdatedMeta->value = $value;
            $this->refreshLoadedMetaItems();
        } else {
            foreach ($key as $keyItem => $keyItemValue) {
                $currentMetaItem[$keyItem] = $currentMetaItem[$keyItem]->first();
                $currentMetaItem[$keyItem]->type = $types[$keyItem];
                $currentMetaItem[$keyItem]->value = $keyItemValue;
                $currentMetaItem[$keyItem]->save();
            }
            foreach ($key as $keyItem => $keyItemValue) {
                $singleUpdatedMeta = $this->getLoadedMeta()->where('id', $currentMetaItem[$keyItem]->id)->first();
                $singleUpdatedMeta->type = $types[$keyItem];
                $singleUpdatedMeta->value = $keyItemValue;
            }
            $this->refreshLoadedMetaItems();
        }
        return true;

    }

    /**
     * create new meta actions . return false if meta with this features already exists
     * note : $key value can not be array in this method
     *
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @return bool
     */
    private function creatingMetaAction($key, $value, $type)
    {
        if (!is_string($key) && !is_array($key)) {
            return false;
        }
        $keyIsArray = false;

        // check if meta already exists
        if (is_array($key)) {
            $keyIsArray = true;
            $currentMetaItem = [];
            foreach ($key as $keyItem => $keyItemValue) {
                if (!is_string($keyItem) || is_int($keyItem)) {
                    return false;
                }
                $currentMetaItem[$keyItem] = $this->getLoadedMeta()->where('key', $keyItem);
                if ($currentMetaItem[$keyItem]->count()) { // if meta not founded
                    return false;
                }
            }
        } else {
            $currentMeta = $this->getLoadedMeta()->where('key', $key);
            if ($currentMeta->count()) { // if meta not founded
                return false;
            }
        }

        //determine types
        $types = [];
        if ($type === null && $keyIsArray == false) {
            $type = Meta::guessType($value);
            if ($type == Meta::META_TYPE_COLLECTION || $type == Meta::META_TYPE_ARRAY || $type == Meta::META_TYPE_JSON) {
                $type = Meta::META_TYPE_COLLECTION;
            }
        } elseif ($keyIsArray == true) {
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

        //convert arrays and json's to collection
        if ($keyIsArray == true) {
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

        if ($keyIsArray == false) {
            $newMeta = new \Zoha\Meta\Models\Meta;
            $newMeta->status = true;
            $newMeta->type = $type;
            $newMeta->key = $key;
            $newMeta->value = $value;
            $this->meta()->save($newMeta);
            $this->getLoadedMeta()->add($newMeta);
            $this->refreshLoadedMetaItems();
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
            $this->meta()->saveMany($currentMetaItem);
            foreach ($currentMetaItem as $singleCreatedMetaItem) {
                $this->getLoadedMeta()->add($singleCreatedMetaItem);
            }
            $this->refreshLoadedMetaItems();
        }
        return true;
    }

    /**
     * updating an existing meta
     *
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @return bool : true on success false if meta not founded
     */
    public function updateMeta($key, $value = null, $type = null)
    {
        return $this->setMeta($key, $value, $type, false);
    }

    /**
     * create new meta . return false if meta with this features already exists
     * note : $key value can not be array in this method
     *
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @return bool
     */
    public function createMeta($key, $value = null, $type = null)
    {
        return $this->setMeta($key, $value, $type, true);
    }

    /**
     * alias of createMeta() method
     * create new meta. return false if meta with this features already exists
     *
     * @param string $key <p> key of meta </p>
     * @param mixed $value
     * @param $type <p>this type must be const from Meta class </p>
     * @return bool
     */
    public function addMeta($key, $value = null, $type = null)
    {
        return $this->creatingMetaAction($key, $value, $type);
    }

    /**
     * increase a meta value , create it if not exists
     *
     * @param string $key
     * @param integer $increaseStep
     * @return bool
     */
    public function increaseMeta($key, $increaseStep = 1)
    {
        $currentValue = $this->getMeta($key, '00');
        if ($currentValue === '00') {
            return $this->createMeta($key, 0);
        } elseif (is_int($currentValue)) {
            $currentValue += $increaseStep;
            return $this->updateMeta($key, $currentValue);
        }
        return false;
    }

    /**
     * decrease a meta value , create it if not exists
     *
     * @param string $key
     * @param int $decreaseStep
     * @return bool
     */
    public function decreaseMeta($key, $decreaseStep = 1)
    {
        $currentValue = $this->getMeta($key, '00');
        if ($currentValue === '00') {
            return $this->createMeta($key, 0, Meta::META_TYPE_INTEGER);
        } elseif (is_int($currentValue)) {
            $currentValue -= $decreaseStep;
            return $this->updateMeta($key, $currentValue);
        }
        return false;
    }
}