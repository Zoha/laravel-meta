<?php


namespace Zoha\Meta\Traits;


use Zoha\Meta\Helpers\MetaHelper as Meta;
use Zoha\Meta\Helpers\MetaCollection;
use Illuminate\Database\Eloquent\Collection;

trait MetableBase
{

    //----------------------------------------- Properties ------------------------------------------//

    public $loadedMetaItems = null;
    public $loadedMeta = null;

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * for get meta morphMany relation
     * or return meta value by given key
     * or add|update meta by given key and value
     *
     * @example : meta('key')               ___> return test meta value
     * @example : meta('test','test value') ___> create or update test meta
     * @example : meta(['my key' => 'my value']] __> setting new metas
     *
     * @param mixed $key <p>name of key for return value or new meta key or array of keys and values</p>
     * @param mixed $value <p>new value of meta</p>
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function meta($key = null, $value = Meta::NO_VALUE_FOR_PARAMETER, $type = null)
    {
        if ($key !== null) {
            return $this->processMetaRequest($key, $value, $type);
        }
        return $this->morphMany('Zoha\Meta\Models\Meta', 'owner');
    }

    /**
     * for get meta morphMany relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function metarelation()
    {
        return $this->morphMany('Zoha\Meta\Models\Meta', 'owner');
    }

    /**
     * return loadedMeta Items in  meta collection type
     *
     * @return MetaCollection
     */
    public function getLoadedMetaItems()
    {
        if ($this->loadedMetaItems == null) {
            $this->refreshLoadedMetaItems();
        }
        return $this->loadedMetaItems;

    }

    /**
     * return loadedMeta property
     * get from database if loadedMeta is null
     *
     * @return Collection
     */
    public function getLoadedMeta()
    {
        if ($this->loadedMeta == null) {
            if(property_exists($this , 'meta')){
                $this->loadedMeta = $this->meta;
            }else{
                $this->loadedMeta = $this->metarelation;
            }
        }
        return $this->loadedMeta;

    }

    /**
     * force to refresh current loadedMeta property
     *
     * @return void
     */
    public function refreshLoadedMeta()
    {
        $this->loadedMeta = $this->meta()->get();
        $this->refreshLoadedMetaItems();
    }

    /**
     * refresh loadedMetaItems property list
     *
     * @return void
     */
    public function refreshLoadedMetaItems()
    {
        $this->loadedMetaItems = new MetaCollection($this);
    }

    /**
     * process given parameters an acording to theme return meta or create | update a new
     *
     * @param mixed $key
     * @param mixed $value
     * @param $type
     * @return mixed
     */
    private function processMetaRequest($key, $value, $type)
    {
        if (is_array($key)) {
            foreach ($key as $keyItem => $value) {
                $setMetaResult = $this->setMeta($keyItem, $value, $type);
                if ($setMetaResult === false) {
                    return false;
                }
            }
        } elseif ($value === Meta::NO_VALUE_FOR_PARAMETER) {
            return $this->getMeta($key, null, $type);
        } else {
            return $this->setMeta($key, $value, $type);
        }
        return true;
    }

    /**
     * return meta data for this model by meta property
     *
     * @param $property
     * @return MetaCollection
     */
    public function __get($property)
    {
        if ($property === "meta") {
            return $this->getLoadedMetaItems();
        }
        return parent::__get($property);
    }

    /**
     * decrease a meta value , create it if not exists
     *
     * @param string $key
     * @param bool $acceptNull
     * @param null $type
     * @return bool
     */
    public function hasMeta($key = null, $acceptNull = false, $type = null)
    {
        if ($key === null) {
            return (bool)($this->getLoadedMeta()->count());
        }
        $meta = $this->getLoadedMeta()->where('key', $key);
        if (!$meta->count()) {
            return false;
        }
        if ($type !== null) {
            $returnedType = $meta->first()->type;
            if ($type !== $returnedType) {
                return false;
            }
        }
        $meta = $this->getMeta($key, null);
        if ($meta === null && $acceptNull === false) {
            return false;
        }
        return true;
    }
}