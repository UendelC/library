<?php

namespace Tests\Unit;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests if an author is associated with a book
     * 
     * @return void
     */
    public function testAnAuthorIdIsRecorded()
    {
        Book::create(
            [
                'title' => 'Cool Title',
                'author_id' => 1
            ]
        );

        $this->assertCount(1, Book::all());
    }
}
