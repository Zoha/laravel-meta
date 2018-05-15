<?php


namespace Zoha\Meta\Helpers;


use Zoha\Meta\Helpers\MetaHelper as Meta;
use Illuminate\Database\Eloquent\Collection;

class MetaCollection extends Collection
{
    //----------------------------------------- Properties ------------------------------------------//

    protected $model;

    protected $temporaryItems = null;

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * constructor : inject metaCollection
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->temporaryItems = clone $model->getLoadedMeta();
        parent::__construct($this->model->getLoadedMeta()->pluck('value', 'key'));
    }

    /**
     * return meta values
     *
     * @param string $property
     * @return string|collection
     */
    public function __get($property)
    {
        $checkHasMeta = $this->model->getLoadedMeta()
            ->where('key', $property)->where('value', '!=', null);

        if ($checkHasMeta->count()) {
            return Meta::returnValue($this->model->getLoadedMeta(), $property);
        }
        return $this->searchMetaName($property);
    }

    /**
     * set new meta value
     *
     * @param $property
     * @param $value
     * @return void
     */
    public function __set($property, $value)
    {
        $inTemporaryItems = $this->temporaryItems->where('key', $property);
        if ($inTemporaryItems->count()) {
            $this->updateTemporaryMeta($value, $inTemporaryItems);
        } else {
            $this->createTemporaryMeta($property, $value, $inTemporaryItems);
        }
    }

    /**
     * save meta changes
     *
     * return bool
     */
    public function save()
    {
        $changedItems = $this->temporaryItems->where('changed', true);
        if ($changedItems->count()) {
            return $this->updateDatabaseAndLoadedMetaList($changedItems);
        }
        return false;
    }

    /**
     * find proper meta name from given property to __get method
     *
     * @param $property
     * @return Collection|null|string
     */
    protected function searchMetaName($property)
    {
        return (strpos($property, '_')) ?
            $this->__get(str_replace('_', ' ', $property)) ?
                $this->__get(str_replace('_', ' ', $property)) :
                $this->__get(str_replace('_', '-', $property))
            : null;
    }

    /**
     * updating an existing meta ( temporary )
     *
     * @param $value
     * @param $inTemporaryItems
     */
    protected function updateTemporaryMeta($value, $inTemporaryItems): void
    {
        $inTemporaryItems = $inTemporaryItems->first();
        $inTemporaryItems->value = $value;
        $inTemporaryItems->changed = true;
        $inTemporaryItems->alreadyExists = true;
    }

    /**
     * create new temporary meta
     *
     * @param $property
     * @param $value
     * @param $inTemporaryItems
     */
    protected function createTemporaryMeta($property, $value, $inTemporaryItems): void
    {
        $meta = new \Zoha\Meta\Models\Meta();
        $meta->key = $property;
        $meta->value = $value;
        $meta->changed = true;
        $inTemporaryItems->alreadyExists = false;
        $this->temporaryItems->add($meta);
    }

    /**
     * create new items in database from temporary list
     *
     * @param $newItems
     */
    protected function finalCreateMetaFromTemporary($newItems): void
    {
        foreach ($newItems as $newItemKey => $newItemValue) {
            $this->model->loadedMeta = $this->model->getLoadedMeta()->reject(function ($value) use ($newItemKey
            ) {
                return $value->key === $newItemKey;
            });
        }
        $this->model->createMeta($newItems);
    }

    /**
     * update existing meta items in database and update loaded meta
     *
     * @param $existsItems
     * @param $newItems
     * @return mixed
     */
    protected function updateExistingItemsFromTemporary($existsItems, $newItems)
    {
        foreach ($existsItems as $existsItemKey => $existsItemValue) {
            $onProcessMetaItem = $this->model->getLoadedMeta()->where('key', $existsItemKey)->first();
            unset($onProcessMetaItem->changed);
            unset($onProcessMetaItem->alreadyExists);
            $result = $this->model->updateMeta($existsItemKey, $existsItemValue);
            if ($result == false) {
                $newItems[$existsItemKey] = $existsItemValue;
            }
        }
        return $newItems;
    }

    /**
     * separate items to create and update .
     * and send them to other methods to save in db
     *
     * @param $changedItems
     * @return bool
     */
    protected function updateDatabaseAndLoadedMetaList($changedItems): bool
    {
        $existsItems = [];
        $newItems = [];
        //separate update items and create items .
        foreach ($changedItems as $changedItem) {
            if ($changedItem->alreadyExists === true) {
                $existsItems[$changedItem->key] = $changedItem->value;
            } else {
                $newItems[$changedItem->key] = $changedItem->value;
            }
        }

        // update results in database
        if (count($existsItems)) {
            $newItems = $this->updateExistingItemsFromTemporary($existsItems, $newItems);
        }
        if (count($newItems)) {
            $this->finalCreateMetaFromTemporary($newItems);
        }
        return true;
    }
}