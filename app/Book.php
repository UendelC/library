<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $guarded = [];

    /**
     * Adds path
     * 
     * @return void
     */
    public function path()
    {
        return '/books/' . $this->id;
    }
}
