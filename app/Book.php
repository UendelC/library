<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $guarded = [];
    protected $attributes = [];

    /**
     * Adds path
     * 
     * @return void
     */
    public function path()
    {
        return '/books/' . $this->id;
    }

    /**
     * Adds relationship Author has many Books
     * 
     * @param  $author to be added in relationship
     * @return void
     */
    public function setAuthorIdAttribute($author)
    {
        $this->attributes['author_id'] = (Author::firstOrCreate(
            [
                'name' => $author,
            ]
        ))->id;
    }
}
