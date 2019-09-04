<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'external_id',
        'name',
        'logo',
    ];

    public function matchesWon()
    {
        return $this->hasMany(Match::class, 'team_won_id');
    }

    public function matchesLost()
    {
        return $this->hasMany(Match::class, 'team_lost_id');
    }
}
