<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\OpenLigaInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Matches;

class MatchesController extends Controller
{
    private $openLiga;

    public function __construct(OpenLigaInterface $openLiga)
    {
        $this->openLiga = $openLiga;
    }

    /**
     * fetches upcoming matches
     *
     * @return mixed
     * @throws
     */

    public function upcoming()
    {
        try {
            $matches = $this->openLiga->upcomingMatches ();
            if (array_key_exists ('errors', $matches)) {
                throw new \Exception($matches['message'], $matches['code']);
            }
            return View::make('matches.upcoming')
                ->with('matches', $matches);
        } catch(\Exception $e) {
            return redirect()->back()
                ->with('msg', ['errors' => $e->getMessage()]);
        }
    }

    /**
     * fetches matches matches
     *
     * @params Illuminate\Http\Request;
     * @return mixed
     * @throws
     */

    public function matchesCurrentSeason(Request $request)
    {
        $matches = Cache::get('matchesCurrentSeas');
        $matchesSrv = new Matches();
        $paginatedItems = $matchesSrv->buildPaginator ($matches, $request);
        if ($matches != null) {
            return View::make('matches.index')
                ->with('matches', $paginatedItems);
        }
        try {
            $matches = $this->openLiga->matchesCurrentSeason ();
            $paginatedItems = $matchesSrv->buildPaginator ($matches, $request);
            Cache::rememberForever('matchesCurrentSeas', function () use ($matches) {
                return $matches;
            });
            if (array_key_exists ('errors', $matches)) {
                throw new \Exception($matches['message'], $matches['code']);
            }
            return View::make('matches.index')
                ->with('matches', $paginatedItems);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('msg', ['errors' => $e->getMessage()]);
        }
    }
}