<?php

namespace App\Console\Commands\Currencies;

use App\Currency;
use App\CurrencyInfo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ParseCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing all currencies from `currencies` table';

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

        $data = $currencyModel->getUnparsedCurrencies();

        foreach ($data as $item) {
            $item = $item->getAttributes();

            $this->getCurrencyByName($item['name']);

            $currencyModel->setParsedCurrency($item['id']);
        }
    }

    private function getCurrencyByName($currency)
    {
        $client = new Client();
        $currencyInfoModel = new CurrencyInfo();

        $counter = 0;

        while ($counter <= 14) {
            $date = date('Ymd', strtotime("- $counter day"));

            $url = 'https://old.bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=' . $currency . '&date=' . $date . '&json';

            $apiResponse = $client->get($url);
            $response = json_decode($apiResponse->getBody());

            $response = array_shift($response);

            if (!empty($response)) {
                $currencyInfoModel->insertCurrencyInfo($response);
            }

            $counter++;
        }
    }
}
