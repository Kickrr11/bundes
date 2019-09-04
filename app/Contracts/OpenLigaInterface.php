<?php

namespace App\Contracts;

interface OpenLigaInterface
{
    public function upcomingMatches();
    public function fetchMatches();
}