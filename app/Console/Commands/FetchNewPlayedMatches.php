<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\OpenLigaInterface;

class FetchNewPlayedMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:matches';

    private $openLiga;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching finished matches Open liga db ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OpenLigaInterface $openLiga)
    {
        $this->openLiga = $openLiga;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->openLiga->fetchMatches();
    }
}
