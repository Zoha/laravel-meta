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
        parent::__construct($this->model->getLoadedMeta()->pluck('key','value'));
    }

    /**
     * return meta values
     *
     * @param string $property
     * @return string|collection
     */
    public function __get($property)
    {
        $checkHasMeta = $this->model->getLoadedMeta()->where('key', $property)->where('value', '!=', null);
        if ($checkHasMeta->count()) {
            return Meta::returnValue($this->model->getLoadedMeta() , $property);
        }
        if(strpos($property,'_')){
            $property = str_replace('_',' ',$property);
            return $this->__get($property);
        }elseif(strpos($property,' ')){
            $property = str_replace(' ','-',$property);
            return $this->__get($property);
        }
        return null; // if value was not founded return null
    }

    public function __set($property , $value)
    {
        $inTemporaryItems = $this->temporaryItems->where('key' , $property);
        if($inTemporaryItems->count()){
            $inTemporaryItems = $inTemporaryItems->first();
            $inTemporaryItems->value = $value;
            $inTemporaryItems->changed = true;
            $inTemporaryItems->alreadyExists = true;
        }else{
            $meta = new \Zoha\Meta\Models\Meta();
            $meta->key = $property;
            $meta->value = $value;
            $meta->changed = true;
            $inTemporaryItems->alreadyExists = false;
            $this->temporaryItems->add($meta);
        }
    }

    public function save()
    {
        $changedItems = $this->temporaryItems->where('changed' , true);
        if($changedItems->count()){
            $existsItems = [];
            $newItems = [];
            foreach($changedItems as $changedItem){
                if($changedItem->alreadyExists === true){
                    $existsItems[$changedItem->key] = $changedItem->value;
                }else{
                    $newItems[$changedItem->key] = $changedItem->value;
                }
            }
            if(count($existsItems)){
                foreach($existsItems as $existsItemKey => $existsItemValue){
                    $onProcessMetaItem = $this->model->getLoadedMeta()->where('key' , $existsItemKey)->first();
                    unset($onProcessMetaItem->changed);
                    unset($onProcessMetaItem->alreadyExists);
                    $result = $this->model->updateMeta($existsItemKey , $existsItemValue);
                    if($result == false){
                        $newItems[$existsItemKey] = $existsItemValue;
                    }
                }
            }
            if(count($newItems)){
                foreach($newItems as $newItemKey => $newItemValue){
                    $this->model->loadedMeta = $this->model->getLoadedMeta()->reject(function ($value) use ($newItemKey) {
                        return $value->key === $newItemKey;
                    });
                }
                $this->model->createMeta($newItems);
            }
            return true;
        }
        return false;
    }
}