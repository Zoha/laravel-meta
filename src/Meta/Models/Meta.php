<?php

namespace Zoha\Meta\Models;

use Illuminate\Database\Eloquent\Model;
use Zoha\Meta\Database\Factories\MetaFactory;

class Meta extends Model
{

    //----------------------------------------- Properties ------------------------------------------//

    protected $table = 'meta';
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $fillable = [
        'key',
        'value',
        'owner_type',
        'owner_id',
        'status',
        'type'
    ];

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * morphTo relation with other models
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    public function getMetaTableName()
    {
        return $this->table;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('meta.tables.default', 'meta'));
    }

    public static function factory()
    {
        return MetaFactory::new();
    }
}
