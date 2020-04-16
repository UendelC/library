<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];

    protected $dates = ['dob'];

    /**
     * Unecessarily parses dob to carbon
     * 
     * @param  $dob to be parsed
     * @return void
     */
    public function setDobAttribute($dob)
    {
        $this->attributes['dob'] = Carbon::parse($dob);
    }
}
