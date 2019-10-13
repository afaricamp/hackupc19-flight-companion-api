<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'total_score',
        'multiplier',
        'status',
        'arrival',
        'departure',
        'airport_id',
    ];

    public function airport()
    {
        return $this->hasOne(Airport::class, 'id', 'airport_id');
    }

    public function user_games()
    {
        return $this->hasMany(UserGame::class);
    }
}
