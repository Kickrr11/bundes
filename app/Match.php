<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'team_won_id',
        'team_lost_id',
        'external_id',
        'draw',
        'finished'
    ];

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
