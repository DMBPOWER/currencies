<?php

namespace App\Console\Commands\Currencies;

use App\Currency;
use App\CurrencyInfo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ParseCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse currency by date';

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
     * @return mixed
     */
    public function handle()
    {
        $currencyModel = new Currency();

        $data = $currencyModel->getAvailableCurrencies();

        foreach ($data as $item) {
            $item = $item->getAttributes();

            $this->getCurrencyByName($item['name']);
        }
    }

    private function getCurrencyByName($currency)
    {
        $client = new Client();
        $currencyInfoModel = new CurrencyInfo();

        $date = date('Ymd');

        $url = 'https://old.bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=' . $currency . '&date=' . $date . '&json';

        $apiResponse = $client->get($url);
        $response = json_decode($apiResponse->getBody());

        $response = array_shift($response);

        if (!empty($response)) {
            $currencyInfoModel->insertCurrencyInfo($response);
        }
    }
}
