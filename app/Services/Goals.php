<?php

namespace App\Services;

use App\Goal;

class Goals
{
    /**
     * Stores goals
     *
     * @return mixed
     * @throws
     */

    public function store($goals, $matchObj)
    {

        foreach ($goals as $goal) {
            Goal::create (
                [
                    'external_goalgetter_id' => $goal['GoalGetterID'],
                    'goalgetter' => $goal['GoalGetterName'],
                    'match_id' => $matchObj->id
                ]
            );
        }
    }

    /**
     * Player goals search
     *
     * @return mixed
     * @throws
     */

    public function searchPlayerGoals($player)
    {
        $search = Goal::where('goalgetter', 'like', '%' . $player . '%')->count();

        return $search;
    }
}