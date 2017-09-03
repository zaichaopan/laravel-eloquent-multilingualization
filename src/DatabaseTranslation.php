<?php

namespace Zai\Translate;

use Illuminate\Database\Eloquent\Model;

class DatabaseTranslation extends  Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Translations';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array'
    ];
}
