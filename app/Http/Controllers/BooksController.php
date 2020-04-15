<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Simple Store function
     * 
     * @return void
     */
    public function store()
    {
        Book::create($this->validateRequest());
    }

    /**
     * Updates a book
     * 
     * @param  $book item to be updated
     * @return void
     */
    public function update(Book $book)
    {
        $book->update($this->validateRequest());
    }

    /**
     * @return mixed
     */
    protected function validateRequest()
    {
        return request()->validate(
            [
                'title' => 'required',
                'author' => 'required'
            ]
        );
    }
}
