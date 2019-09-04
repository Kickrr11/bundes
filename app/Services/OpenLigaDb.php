<?php

namespace App\Services;

use App\Contracts\OpenLigaInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use App\Team;
use App\Match;
use App\Goal;
use Carbon\Carbon;
use App\Services\Teams;
use App\Services\Matches;

class OpenLigaDb implements OpenLigaInterface
{
    /**
     * Makes http calls to Open Liga Db using guzzle
     *
     * @param string  $uri Array of post fields to be posted
     * @param array  $headers    Array of http headers
     *
     * @return mixed
     * @throws
     */

    private function makeHttpCall($uri, $headers = [])
    {
        $client = new Client(
            [
                'base_uri' => 'https://www.openligadb.de/api/ ',
                'headers' => $headers
            ]
        );
        try {
            $response = $client->request ('get' , $uri);
            return $response->getBody ()->getContents ();
        } catch (ClientException $e) {

            return [
                'errors' => [
                    'success' => false,
                    'message' => $e->getMessage (),
                    'status_code' => $e->getCode ()
                ]
            ];
        } catch (ServerException $ser) {
            return [
                'errors' => [
                    'success' => false,
                    'message' => $ser->getMessage (),
                    'status_code' => $ser->getCode ()
                ]
            ];
        } catch (ConnectException $connectException) {
            return [
                'errors' => [
                    'success' => false,
                    'message' => $connectException->getMessage (),
                    'status_code' => $connectException->getCode ()
                ]
            ];
        }
    }

    /**
     * Makes http calls to Open Liga Db using guzzle for fetching upcoming matches
     *
     * @return mixed
     * @throws
     */

    public function upcomingMatches()
    {
        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
        $response = $this->makeHttpCall('getmatchdata/bl1', $headers);
        $response = json_decode ($response, true);
        return $response;
    }

    /**
     * Makes http calls to Open Liga Db using guzzle for fetching season matches
     *
     * @return mixed
     * @throws
     */

    public function matchesCurrentSeason()
    {
        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
        $response = $this->makeHttpCall('getmatchdata/bl1/2019', $headers);
        $res = json_decode ($response, true);

        return $res;
    }

    /**
     * Stores or updates existing matches
     *
     * @return mixed
     * @throws
     */

    public function fetchMatches()
    {
        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
        //current season
        $date = date("Y");
        $response = $this->makeHttpCall('getmatchdata/bl1/'. $date, $headers);
        $matches = json_decode ($response, true);
        $teams = Team::all();
        if ($teams->isEmpty ()) {
            $teamsSrv = new Teams();
            $teamsData = $teamsSrv->teamsBuildData($matches);
            $teamsSrv->store ($teamsData);
        }
        $matchesSrv = new Matches();
        $response = $matchesSrv->storeOrUpdate($matches);
        if (array_key_exists ('errors', $response)) {
            return $response;
        }
        return "Matches fetched";
    }
}