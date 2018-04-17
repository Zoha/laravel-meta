<?php

namespace Zoha\Meta\Models;

use Illuminate\Database\Eloquent\Model;

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
}
