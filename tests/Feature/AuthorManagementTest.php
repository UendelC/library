<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tests store of author
     * 
     * @return void
     */
    public function testAnAuthorCanBeCreated()
    {

        $this->post('/authors', $this->_dados());

        $author = Author::all();

        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1988/14/05', $author->first()->dob->format('Y/d/m'));
    }

    /**
     * Test
     * 
     * @return void
     */
    public function testANameIsRequired()
    {
        $response = $this->post(
            '/authors',
            array_merge($this->_dados(), ['name' => ''])
        );

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test dob requirement
     * 
     * @return void
     */
    public function testADobIsRequired()
    {
        $response = $this->post(
            '/authors',
            array_merge($this->_dados(), ['dob' => ''])
        );

        $response->assertSessionHasErrors('dob');
    }

    /**
     * Helper
     * 
     * @return data
     */
    private function _dados()
    {
        return ['name' => 'Author Name', 'dob' => '05/14/1988'];
    }
}
