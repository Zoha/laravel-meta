<?php


namespace Zoha\Meta\Traits;

use Illuminate\Database\Query\Builder;
use Zoha\Meta\Helpers\MetaHelper as Meta;

trait MetaClauses
{
    /**
     * lazy load meta
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithMeta($query)
    {
        return $query->with('metarelation');
    }

    /**
     * add scope where clause for filter collection data using meta
     *
     * @examples
     * whereMeta('test' , 'test value')
     * whereMeta('test' , '!=' null)
     * whereMeta(['test1' => 'test1 value' , 'test2' => 'test2 value' ])
     * whereMeta(['test1' => 'test1 value' , 'test2' => 'test2 value' ])
     * whereMeta([['test1' , '=' , 'test1 value' ] , ['test2' , 'test2 value']])
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $key
     * @param string $operator
     * @param string $value
     * @param boolean $orWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMeta(
        $query,
        $key,
        $operator = Meta::NO_VALUE_FOR_PARAMETER,
        $value = Meta::NO_VALUE_FOR_PARAMETER,
        $orWhere = false
    ) {
        $type = 'where';
        if ($orWhere) {
            $type = 'orWhere';
        }
        if (!is_string($key) && !is_array($key)) {
            return $query;
        }
        if (is_array($key)) {
            foreach ($key as $conditionKey => $conditionGroup) {
                if (!is_array($conditionGroup)) { // if conditions are just key and value
                    $query = call_user_func_array(
                        [$this, 'scopeWhereMeta'],
                        [$query, $conditionKey, '=', $conditionGroup, $orWhere]);
                } else {
                    $conditionGroup = array_prepend($conditionGroup, $query);
                    if (count($conditionGroup) == 2) {
                        $conditionGroup[] = Meta::NO_VALUE_FOR_PARAMETER;
                        $conditionGroup[] = Meta::NO_VALUE_FOR_PARAMETER;
                    }
                    if (count($conditionGroup) == 3) {
                        $conditionGroup[] = Meta::NO_VALUE_FOR_PARAMETER;
                    }
                    $conditionGroup[] = $orWhere;
                    $query = call_user_func_array([$this, 'scopeWhereMeta'], $conditionGroup);
                }
            }
            return $query;
        }
        $query->{$type . 'Has'}('meta', function ($query) use ($key, $operator, $value) {
            $query->where('key', $key);
            if ($operator === Meta::NO_VALUE_FOR_PARAMETER && $value === Meta::NO_VALUE_FOR_PARAMETER) {
                $query->where('value', null);
            } elseif ($value === Meta::NO_VALUE_FOR_PARAMETER) {
                $query->where('value', Meta::convertMetaValueForSearch($operator));
            } else {
                $query->where('value', $operator, Meta::convertMetaValueForSearch($value));
            }
        });
        return $query;
    }

    /**
     * add scope orWhere clause for filter collection data using meta
     *
     * @examples
     * orWhereMeta('test' , 'test value')
     * orWhereMeta('test' , '!=' null)
     * orWhereMeta(['test1' => 'test1 value' , 'test2' => 'test2 value' ])
     * orWhereMeta(['test1' => 'test1 value' , 'test2' => 'test2 value' ])
     * orWhereMeta([['test1' , '=' , 'test1 value' ] , ['test2' , 'test2 value']])
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $key
     * @param string $operator
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereMeta($query, $key, $operator = null, $value = null)
    {
        return $this->scopeWhereMeta($query, $key, $operator, $value, true);
    }

    /**
     * clause for filter items that has given meta and value is between defined values
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values of min and max value
     * @param boolean $orWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMetaBetween($query, $key, $values = [0, 100], $orWhere = false)
    {
        if (count($values) != 2 || !isset($values[0]) || !isset($values[1]) || !is_int($values[0]) || !is_int($values[1])) {
            return $query;
        }
        $type = 'where';
        if ($orWhere) {
            $type = 'orWhere';
        }
        $query->{$type . 'Has'}('meta', function ($query) use ($key, $values) {
            $query->where('key', $key);
            $query->whereBetween('value', $values);
        });
        return $query;
    }

    /**
     * clause (or) for filter items that has given meta and value is between defined values
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values of min and max value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereMetaBetween($query, $key, $values = [0, 100])
    {
        return $this->scopeWhereMetaBetween($query, $key, $values, true);
    }

    /**
     * clause for filter items that has given meta and value is  not between defined values
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values of min and max value
     * @param boolean $orWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMetaNotBetween($query, $key, $values = [0, 100], $orWhere = false)
    {
        if (count($values) != 2 || !isset($values[0]) || !isset($values[1]) || !is_int($values[0]) || !is_int($values[1])) {
            return $query;
        }
        $type = 'where';
        if ($orWhere) {
            $type = 'orWhere';
        }
        $query->{$type . 'Has'}('meta', function ($query) use ($key, $values) {
            $query->where('key', $key);
            $query->whereNotBetween('value', $values);
        });
        return $query;
    }

    /**
     * clause (or) for filter items that has given meta and value is not between defined values
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values of min and max value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereMetaNotBetween($query, $key, $values = [0, 100])
    {
        return $this->scopeWhereMetaNotBetween($query, $key, $values, true);
    }

    /**
     * filter items that have one of the given values
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values
     * @param boolean $orWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMetaIn($query, $key, $values = [], $orWhere = false)
    {
        if (!is_array($values)) {
            return $query;
        }
        $type = 'where';
        if ($orWhere) {
            $type = 'orWhere';
        }
        $query->{$type . 'Has'}('meta', function ($query) use ($key, $values) {
            $query->where('key', $key);
            $query->whereIn('value', $values);
        });
        return $query;
    }

    /**
     * filter items that have one of the given values ( or clause )
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereMetaIn($query, $key, $values = [])
    {
        return $this->scopeWhereMetaIn($query, $key, $values, true);
    }

    /**
     * filter items that don't have one of the given values
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values
     * @param boolean $orWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMetaNotIn($query, $key, $values = [], $orWhere = false)
    {
        if (!is_array($values)) {
            return $query;
        }
        $type = 'where';
        if ($orWhere) {
            $type = 'orWhere';
        }
        $query->{$type . 'Has'}('meta', function ($query) use ($key, $values) {
            $query->where('key', $key);
            $query->whereNotIn('value', $values);
        });
        return $query;
    }

    /**
     * filter items that don't have one of the given values ( or clause )
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param array $values
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereMetaNotIn($query, $key, $values = [])
    {
        return $this->scopeWhereMetaNotIn($query, $key, $values, true);
    }

    /**
     * filter items that have null value for specific key
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param boolean $orWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMetaNull($query, $key, $orWhere = false)
    {
        $type = 'where';
        if ($orWhere) {
            $type = 'orWhere';
        }
        $query->{$type . 'Has'}('meta', function ($query) use ($key) {
            $query->where('key', $key);
            $query->whereNull('value');
        });
        return $query;
    }

    /**
     * filter items that have null value for specific key ( or clause )
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereMetaNull($query, $key)
    {
        return $this->scopeWhereMetaNull($query, $key, true);
    }

    /**
     * filter items that have not null value for specific key
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param boolean $orWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMetaNotNull($query, $key, $orWhere = false)
    {
        $type = 'where';
        if ($orWhere) {
            $type = 'orWhere';
        }
        $query->{$type . 'Has'}('meta', function ($query) use ($key) {
            $query->where('key', $key);
            $query->whereNotNull('value');
        });
        return $query;
    }

    /**
     * filter items that have not null value for specific key ( or clause )
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereMetaNotNull($query, $key)
    {
        return $this->scopeWhereMetaNotNull($query, $key, true);
    }

    /**
     * filter items that have specific meta .
     *
     * @param Builder $query
     * @param string $key
     * @param bool $countNull
     * @param null $type
     * @param bool $orWhere
     * @return Builder
     */
    public function scopeWhereMetaHas($query, $key = null, $countNull = false, $type = null, $orWhere = false)
    {
        $typeWhere = 'where';
        if ($orWhere) {
            $typeWhere = 'orWhere';
        }
        $query->{$typeWhere . 'Has'}('meta', function ($query) use ($key, $countNull, $type) {
            if ($key === null) {
                return $query;
            }
            $query->where('key', $key);
            if ($countNull === false) {
                $query->where('value', '!=', null);
            }
            if ($type !== null) {
                $query->where('type', '=', $type);
            }
            return $query;
        });
        return $query;
    }

    /**
     * filter items that have specific meta ( or clause )
     *
     * @param Builder $query
     * @param string $key
     * @param bool $countNull
     * @param null $type
     * @return Builder
     */
    public function scopeOrWhereMetaHas($query, $key = null, $countNull = false, $type = null)
    {
        return $this->scopeWhereMetaHas($query, $key, $countNull, $type, true);
    }

    /**
     * filter items that doesn't have given meta .
     *
     * @param Builder $query
     * @param string $key
     * @param bool $countNull
     * @param null $type
     * @param bool $orWhere
     * @return Builder
     */
    public function scopeWhereMetaDoesntHave($query, $key = null, $countNull = false, $type = null, $orWhere = false)
    {
        $typeWhere = 'where';
        if ($orWhere) {
            $typeWhere = 'orWhere';
        }
        $query->{$typeWhere . 'DoesntHave'}('meta', function ($query) use ($key, $countNull, $type) {
            if ($key === null) {
                return $query;
            }
            $query->where('key', $key);
            if ($countNull === false) {
                $query->where('value', '!=', null);
            }
            if ($type !== null) {
                $query->where('type', '=', $type);
            }
            return $query;
        });
        return $query;
    }

    /**
     * filter items that doesn't have given meta .( or clause )
     *
     * @param Builder $query
     * @param string $key
     * @param bool $countNull
     * @param null $type
     * @return Builder
     */
    public function scopeOrWhereMetaDoesntHave($query, $key = null, $countNull = false, $type = null)
    {
        return $this->scopeWhereMetaDoesntHave($query, $key, $countNull, $type, true);
    }

    /**
     * filter items using value of meta
     *
     *
     * @param $query
     * @param $key
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderByMeta($query , $key , $direction = 'asc'){
        $this->countOfMetaJoins += 1;
        return $query->leftJoin('meta as meta'.$this->countOfMetaJoins, function ($q) use($key) {
            $q->on('meta'.$this->countOfMetaJoins.'.owner_id', '=', $this->getTable().".id");
            $q->where('meta'.$this->countOfMetaJoins.'.owner_type', '=', static::class);
            $q->where('meta'.$this->countOfMetaJoins.'.key', $key);
        })
            ->orderByRaw("CASE (meta".$this->countOfMetaJoins.".key)
              WHEN '$key' THEN 1
              ELSE 0
              END
              DESC")
            ->orderBy('meta'.$this->countOfMetaJoins.'.value', strtoupper($direction))
            ->select($this->getTable().".*");
    }
}