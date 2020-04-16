<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testaBookCanBeAddedtoTheLibrary()
    {
        $this->withoutExceptionHandling();

        $response = $this->post(
            '/books',
            [
                'title' => 'Cool Book Title',
                'author' => 'Victor'
            ]
        );

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /**
     * A title is required
     * 
     * @return void
     */
    public function testaTitleIsRequired()
    {
        $response = $this->post(
            '/books',
            [
                'title' => '',
                'author' => 'Victor'
            ]
        );

        $response->assertSessionHasErrors('title');
    }

    /**
     * Tests if there's an author
     * 
     * @return void
     */
    public function testAnAuthorIsRequired()
    {
        $response = $this->post(
            '/books',
            [
                'title' => 'Cool Title',
                'author' => ''
            ]
        );

        $response->assertSessionHasErrors('author');
    }

    /**
     * Tests if a book can be updated
     * 
     * @return void
     */
    public function testABookCanBeUpdated()
    {
        $this->post(
            '/books',
            [
                'title' => 'Cool Title',
                'author' => 'Victor'
            ]
        );

        $book = Book::first();

        $response = $this->patch(
            $book->path(),
            [
                'title' => 'New Title',
                'author' => 'New Author'
            ]
        );

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }

    /**
     * Tests if a book can be deleted
     * 
     * @return mixed
     */
    public function testABookCanBeDeleted()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', ['title' => 'Cool Title', 'author' => 'Victor']);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
