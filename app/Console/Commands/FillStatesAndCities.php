<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\State;
use Illuminate\Console\Command;
use Daaner\NovaPoshta\Models\Address;

class FillStatesAndCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $adr = new Address;
        $adr->setLimit(10000);
        $adr->setPage(0);
        $cities = $adr->getCities();
        foreach ($cities['result'] as $city) {
            $stateName = $city['AreaDescription'];
            $cityName = $city['Description'];
            echo $stateName.':'.$cityName.PHP_EOL;
            $state = State::where('name', '=', $stateName)->first();
            if (empty($state)) {
                $state = new State();
                $state->name = $stateName;
                $state->save();
            }
            $city = new City();
            $city->state_id = $state->id;
            $city->name = $cityName;
            $city->save();
        }
    }
}
