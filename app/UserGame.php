<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGame extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'flight_id',
        'score',
        'multiplier',
    ];

    public function flight()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
