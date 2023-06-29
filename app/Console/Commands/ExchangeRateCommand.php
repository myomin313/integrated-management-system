<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\SalaryManagement\ExchangeRate;
use Illuminate\Support\Facades\Http;

class ExchangeRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchangerate:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get exchange rate from myanmar central bank daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get('http://forex.cbm.gov.mm/api/latest');
    
        $jsonData = $response->json();

        $jpn_rate = str_replace( ',', '', $jsonData['rates']['JPY']);
        $usd_rate = str_replace( ',', '', $jsonData['rates']['USD']);
        $date = Carbon::now()->format("Y-m-d");
        
        $exchange_rate = ExchangeRate::where("date","=",$date)->first();
        if(!$exchange_rate){
            $exchange_rate = new ExchangeRate();
            $exchange_rate->created_by = 1;
        }
        $exchange_rate->date = $date;
        $exchange_rate->dollar = $usd_rate;
        $exchange_rate->yen = $jpn_rate;
        $exchange_rate->save();

        //previous day
        $pre_date = Carbon::now()->subDay()->format("Y-m-d");
        $exchange_rate = ExchangeRate::where("date","=",$pre_date)->first();
        if(!$exchange_rate){
            $exchange_rate = new ExchangeRate();
            $exchange_rate->created_by = 1;
        }
        $exchange_rate->dollar = $usd_rate;
        $exchange_rate->yen = $jpn_rate;
        $exchange_rate->save();

        $this->info("Successfully save excchange rate for ".$date);
    }
}
