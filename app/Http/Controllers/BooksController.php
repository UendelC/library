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
        $book = Book::create($this->validateRequest());
        return redirect($book->path());
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
        return redirect($book->path());
    }

    /**
     * Deletes a book
     * 
     * @param  $book to be deleted
     * @return void
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/books');
    }

    /**
     * Helper function that validates request
     * 
     * @return mixed
     */
    protected function validateRequest()
    {
        return request()->validate(
            [
                'title' => 'required',
                'author_id' => 'required'
            ]
        );
    }
}
