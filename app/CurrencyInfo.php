<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyInfo extends Model
{
    protected $table = 'currencies_info';

    public function insertCurrencyInfo($data)
    {
        $currencyInfo = new CurrencyInfo();

        $currencyInfo->name = $data->cc;
        $currencyInfo->name_translation = $data->txt;
        $currencyInfo->rate = $data->rate;
        $currencyInfo->exchange_date = date('Y-m-d', strtotime($data->exchangedate));

        $currencyInfo->save();
    }

    public function isTodayParsed()
    {
        if (CurrencyInfo::where('exchange_date', date('Y-m-d'))->exists()) {
            return true;
        }

        return false;
    }
}
