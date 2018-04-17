<?php


namespace Zoha\Meta\Traits;

use Illuminate\Database\Eloquent\Collection;

trait DeleteMeta
{
    /**
     * delete a meta from database
     *
     * @param string $key
     * @return bool
     */
    public function deleteMeta($key = null)
    {
        if ($key === null) {
            return $this->truncateMeta();
        }
        $currentMeta = $this->getLoadedMeta()->where('key', $key);
        if (!$currentMeta->count()) { // if meta not founded
            return false;
        }
        $currentMeta = $currentMeta->first();

        $this->loadedMeta = $this->getLoadedMeta()->reject(function ($value) use ($currentMeta) {
            return $value->id === $currentMeta->id;
        });
        $this->refreshLoadedMetaItems();
        \Zoha\Meta\Models\Meta::destroy($currentMeta->id);
        return true;
    }

    /**
     * alias for deleteMeta Method
     *
     * @param string $key
     * @return bool
     */
    public function unsetMeta($key = null)
    {
        return $this->deleteMeta($key);
    }

    /**
     * delete all meta records for current model
     *
     * @return bool
     */
    public function truncateMeta()
    {
        \Zoha\Meta\Models\Meta::destroy($this->getLoadedMeta()->pluck('id')->toArray());
        $this->loadedMeta = new Collection([]);
        return true;
    }
}