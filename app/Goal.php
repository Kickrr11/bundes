<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'match_id',
        'goalgetter',
        'external_goalgetter_id'
    ];

    public function match()
    {
        return $this->belongsTo(Match::class);
    }
}
