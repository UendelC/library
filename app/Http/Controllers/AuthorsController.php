<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    /**
     * Stores authors
     * 
     * @return void
     */
    public function store()
    {
        Author::create($this->validateRequest());
    }

    /**
     * Helper
     * 
     * @return void
     */
    protected function validateRequest()
    {
        return request()->validate(['name' => 'required', 'dob' => 'required']);
    }
}
