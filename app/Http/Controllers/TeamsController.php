<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use Illuminate\Support\Facades\View;
use App\Contracts\OpenLigaInterface;

class TeamsController extends Controller
{
    private $openLiga;

    public function __construct( OpenLigaInterface $openLiga)
    {
        $this->openLiga = $openLiga;
    }

    /**
     * fetches win loss ratio per team
     *
     * @params Request
     * @return mixed
     * @throws
     */

    public function winLossRatio()
    {
        $teams = Team::withCount(['matchesWon', 'matchesLost'])->get();
        if ($teams->isEmpty()) {
            try {
                $matches = $this->openLiga->fetchMatches ();
                if (array_key_exists ('errors', $matches)) {
                    throw new \Exception($matches['errors']['message']);
                }
            } catch (\Exception $e) {
                die($e->getMessage ());
            }
            $teams = Team::withCount(['matchesWon', 'matchesLost'])->get();
        }
        return View::make('teams.index')
            ->with('teams', $teams);
    }
}
