<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    public function getDataByCurrencyAndDate($dates, $currency)
    {
        if ($dates == 'all') {
            return $this->getAllDataByCurrency($currency);
        }

        return Currency::select("currencies.name", "currencies_info.rate", "currencies_info.exchange_date")
                ->join('currencies_info', 'currencies_info.name', '=', 'currencies.name')
                ->where('currencies_info.name', $currency)
                ->whereBetween('currencies_info.exchange_date', [$dates['from'], $dates['to']])
                ->orderBy('currencies_info.exchange_date')
                ->get();
    }

    private function getAllDataByCurrency($currency)
    {
        return Currency::select("currencies.name", "currencies_info.rate", "currencies_info.exchange_date")
                ->join('currencies_info', 'currencies_info.name', '=', 'currencies.name')
                ->where('currencies_info.name', $currency)
                ->orderBy('currencies_info.exchange_date')
                ->get();
    }

    public function getAvailableCurrencies()
    {
        return Currency::where('show', true)->get();
    }

    public function  getUnparsedCurrencies()
    {
        return Currency::where('parsed', false)->get();
    }

    public function setParsedCurrency($id)
    {
        return Currency::where('id', $id)->update(['parsed' => true]);
    }
}
