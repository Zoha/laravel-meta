<?php


namespace Zoha\Meta\Traits;

use Zoha\Meta\Helpers\CreateMetaHelper;
use Zoha\Meta\Helpers\MetaHelper as Meta;
use Zoha\Meta\Helpers\UpdateMetaHelper;

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
        // If the type of operation is specified ( createMeta | updateMeta )
        if ($createMeta !== null) {
            if ($createMeta === false) {
                return UpdateMetaHelper::updateMeta($this, $key, $value, $type);
            } elseif ($createMeta === true) {
                return CreateMetaHelper::createMeta($this, $key, $value, $type);
            }
        }

        // handle array of values that passed for the first argument
        if (is_array($key)) {
            return $this->setMultipleMeta($key, $value);
        }

        // check meta exists or not and execute update or create meta operation
        $currentMeta = $this->getLoadedMeta()->where('key', $key);
        if ($currentMeta->count()) { // if a meta with this key already exists
            return UpdateMetaHelper::updateMeta($this, $key, $value, $type);
        } else {
            return CreateMetaHelper::createMeta($this, $key, $value, $type);
        }
    }

    /**
     * set multiple mta
     *
     * @param $key
     * @param null $value
     * @return bool
     */
    public function setMultipleMeta($key, $value = null)
    {
        if (!is_array($key)) {
            return false;
        }
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
        return CreateMetaHelper::createMeta($this, $key, $value, $type);
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
        $currentValue = $this->getMeta($key, '000');
        if ($currentValue === '000') {
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
        $currentValue = $this->getMeta($key, '000');
        if ($currentValue === '000') {
            return $this->createMeta($key, 0, Meta::META_TYPE_INTEGER);
        } elseif (is_int($currentValue)) {
            $currentValue -= $decreaseStep;
            return $this->updateMeta($key, $currentValue);
        }
        return false;
    }
}