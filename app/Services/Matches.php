<?php

namespace App\Services;

use App\Match;
use App\Team;
use App\Goal;
use Illuminate\Support\Facades\DB;
use App\Services\Goals;
use Illuminate\Pagination\LengthAwarePaginator;

class Matches
{
    public function storeOrUpdate($matches)
    {
        foreach($matches as $k => $match) {
            $existingMatch = Match::where('external_id', $match['MatchID'])->first();
            if(!empty($existingMatch) && $existingMatch->finished == 1) {
                $this->update($existingMatch, $match);
            } elseif(!empty($existingMatch) && $existingMatch->finished == 0) {
                continue;
            } else {
                $this->store($match);
            }
        }
        return [
            'message' => 'Matches service started',
        ];
    }

    public function store($match)
    {
        $winnerLoser = $this->winnerLoser ($match);
        if (array_key_exists ('errors', $winnerLoser)) {
            \Log::error("Could not update match", [
                'message' => $winnerLoser['message'],
            ]);
            exit;
        }
        $winner = $winnerLoser['winner'];
        $lost = $winnerLoser['lost'];
        $finished = $winnerLoser['finished'];
        $draw = $winnerLoser['draw'];
        try {
            DB::beginTransaction ();
            $matchObj = Match::create(
                [
                    'team_won_id' => $winner,
                    'team_lost_id' => $lost,
                    'external_id' => $match['MatchID'],
                    'draw' => $draw,
                    'finished' => $finished
                ]
            );
            if (!empty($match['Goals'])) {
                $goalsSrv = new Goals();
                $goalsSrv->store($match['Goals'], $matchObj);
            }
            DB::commit ();
        } catch(\Exception $e) {
            \Log::error("Could not update match", [
                'message' => $e->getMessage(),
                'code'  => $e->getCode()
            ]);
        }
    }

    public function update($existingMatch, $match)
    {
        $winnerLoser = $this->winnerLoser ($match);
        $winner = $winnerLoser['winner'];
        $lost = $winnerLoser['lost'];
        $finished = $winnerLoser['finished'];
        $draw = $winnerLoser['draw'];

        try {
            DB::beginTransaction ();
            $existingMatch->update([
                'team_won_id' => $winner,
                'team_lost_id' => $lost,
                'external_id' => $match['MatchID'],
                'draw' => $draw,
                'finished' => $finished
            ]);
            if (!empty($match['Goals'])) {
                $goalsSrv = new Goals();
                $goalsSrv->store($match['Goals'], $existingMatch);
            }
            DB::commit ();
        } catch(\Exception $e) {
            \Log::error("Could not update match", [
                'message' => $e->getMessage(),
                'code'  => $e->getCode()
            ]);
        }
    }

    public function winnerLoser($match)
    {
        $team1 = null;
        $team2 = null;
        if (array_key_exists ('Team1', $match)) {
            $team1 = $match['Team1']['TeamId'];
        }
        if (array_key_exists ('Team2', $match)) {
            $team2 = $match['Team2']['TeamId'];
        }
        $winner = 0;
        $draw = 1;
        $lost = 0;
        $team1 = Team::where('external_id', $team1)->first();
        $team2 = Team::where('external_id', $team2)->first();
        if ($team1 == null || $team1 == null) {
            return [
                'message' => 'Team not found',
                'code'  => 500
            ];
        }
        $finished = 1;
        if (array_key_exists (0, $match['MatchResults']) || $match['MatchIsFinished'] == true) {
            $endResult = $match['MatchResults'][0];
            $finished = 0;
            if($endResult['PointsTeam1'] > $endResult['PointsTeam2']) {
                $winner = $team1->id;
                $lost = $team2->id;
            } elseif($endResult['PointsTeam2'] > $endResult['PointsTeam1']) {
                $winner = $team2->id;
                $lost = $team1->id;
            } else {
                $draw = 0;
            }
        }

        return [
            'winner' => $winner,
            'lost' => $lost,
            'draw' => $draw,
            'finished' => $finished,
        ];
    }

    public function buildPaginator($matches, $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($matches);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());

        return $paginatedItems;
    }
}