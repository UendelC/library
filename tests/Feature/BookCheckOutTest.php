<?php

namespace Tests\Feature;

use App\Book;
use App\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookCheckOutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tests endpoint
     * 
     * @return void
     */
    public function testABookCanBeCheckedOutByASignedInUser()
    {
        $book = factory(Book::class)->create();
        $this->actingAs($user = factory(User::class)->create())
            ->post('/checkout/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /**
     * Only auth users can checkout a book
     * 
     * @return void
     */
    public function testOnlySignedInUsersCanCheckOutABook()
    {
        $book = factory(Book::class)->create();
        $this->post('/checkout/' . $book->id)
            ->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());
    }

    /**
     * Testa id of book
     * 
     * @test
     * @return void
     */
    public function testOnlyRealBooksCanBeCheckedOut()
    {
        $this->actingAs(
            $user = factory(User::class)->create()
        )->post('/checkout/123')->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    /**
     * Testa
     * 
     * @test
     * @return void
     */
    public function testABookCanBeCheckedInByASignedUser()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('/checkout/' . $book->id);

        $this->actingAs($user)->post('/checkin/' . $book->id);


        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /**
     * Test
     * 
     * @test
     * @return void
     */
    public function testOnlySignedInUsersCanCheckInABook()
    {
        $book = factory(Book::class)->create();

        $this->actingAs(
            factory(User::class)->create()
        )->post('/checkout/' . $book->id);

        Auth::logout();

        $this->post('/checkin/' . $book->id)->assertRedirect('/login');
        $this->assertCount(1, Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }

    /**
     * Test
     * 
     * @return void
     */
    public function testA404IsThrownIfABookIsNotCheckedOutFirst()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('/checkin/' . $book->id)->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }
}
