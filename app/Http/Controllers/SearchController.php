<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Goal;
use Illuminate\Support\Facades\View;
use App\Services\Goals;

class SearchController extends Controller
{
    /**
     * search player goals view
     *
     * @return mixed
     * @throws
     */

    public function searchPlayerGoals()
    {
        return View::make('search.player-goals');
    }

    /**
     * search player goals view
     *
     * @params Request
     * @return mixed
     * @throws
     */

    public function searchPlayer(Request $request)
    {
        $this->validate ($request, [
            'last_name' => 'required'
        ]);

        $player = $request->input('last_name');
        $goalSrv = new Goals();
        try {
            $search = $goalSrv->searchPlayerGoals($player);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage ());
        }
        $searchResult = $player. ' has '. $search . ' goals this season';
        if ($search == 0) {
            $searchResult = "No goals found";
        }
        return redirect()->back()->with('status', $searchResult);
    }
}
