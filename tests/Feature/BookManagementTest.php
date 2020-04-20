<?php

namespace Tests\Feature;

use App\Book;
use App\Author;
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
            $this->data()
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
            array_merge($this->data(), ['author_id' => ''])
        );

        $response->assertSessionHasErrors('author_id');
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
            $this->data()
        );

        $book = Book::first();

        $response = $this->patch(
            $book->path(),
            [
                'title' => 'New Title',
                'author_id' => 'New Author'
            ]
        );

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
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
        $this->post('/books', $this->data());

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    /**
     * Tests if a new Author is created if he doesnt exist when creating a book
     * 
     * @return void
     */
    public function testANewAuthorIsAutomaticallyAdded()
    {
        $this->withoutExceptionHandling();
        $this->post(
            '/books',
            [
                'title' => 'Cool Title',
                'author_id' => 'Victor',
            ]
        );

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data()
    {
        return [
            'title' => 'Cool Title',
            'author_id' => 'Victor'
        ];
    }
}
