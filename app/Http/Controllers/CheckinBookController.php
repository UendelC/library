<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class CheckinBookController extends Controller
{
    /**
     * Adiciona login requirement
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Test
     * 
     * @param  $book to be checked in
     * @return void
     */
    public function store(Book $book)
    {
        try {
            $book->checkin(auth()->user());
        } catch (\Exception $e) {
            return response([], 404);
        }
    }
}
