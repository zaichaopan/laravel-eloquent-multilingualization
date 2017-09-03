<?php

namespace Zai\Translate\Tests\Model;

use Zai\Translate\Translatable;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Translatable;

    protected $guarded = [];

    protected $translatables = [
        'title',
        'body'
    ];
}
