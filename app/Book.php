<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $guarded = [];
    protected $attributes = [];

    /**
     * Adds path
     * 
     * @return void
     */
    public function path()
    {
        return '/books/' . $this->id;
    }

    /**
     * A user checks out a book
     * 
     * @return void
     */
    public function checkout($user)
    {
        $this->reservations()->create(
            [
                'user_id' => $user->id,
                'checked_out_at' => now()
            ]
        );
    }

    /**
     * Checks in a book
     * 
     * @return void
     */
    public function checkin($user)
    {
        $reservation = $this->reservations()->where('user_id', $user->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->first();

        if (is_null($reservation)) {
            throw new \Exception();
        }

        $reservation->update(['checked_in_at' => now()]);
    }

    /**
     * Adds relationship Author has many Books
     * 
     * @param  $author to be added in relationship
     * @return void
     */
    public function setAuthorIdAttribute($author)
    {
        $this->attributes['author_id'] = (Author::firstOrCreate(
            [
                'name' => $author,
            ]
        ))->id;
    }

    /**
     * Relatioship between books and reservation
     * 
     * @return void
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
