<?php

namespace App\Services;

use App\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Teams
{
    /**
     * Stores Team
     *
     * @return mixed
     * @throws
     */
    public function store($teamsData)
    {
        $dataToInsert = [];
        foreach ($teamsData as $k => $data) {
            $dataToInsert[$k]['name'] =  $data['TeamName'];
            $dataToInsert[$k]['external_id'] =  $data['TeamId'];
            $dataToInsert[$k]['logo'] =  $data['TeamIconUrl'];
            $dataToInsert[$k]['updated_at'] =  Carbon::now();
        }
        try {
            DB::beginTransaction ();
            Team::insert($dataToInsert);
            DB::commit ();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Could not create team", [
                'message' => $e->getMessage(),
                'code'  => $e->getCode()
            ]);
        }
    }

    /**
     * Builds team data
     *
     * @return mixed
     * @throws
     */

    public function teamsBuildData($matches)
    {
        $matchesCollection = collect($matches);
        $teamsData = $matchesCollection->pluck('Team1')->unique('TeamId');

        return $teamsData;
    }
}