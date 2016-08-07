<?php

namespace app\models;

use Yii;
use app\models\Rates;

class YahooRateProvider implements ExchangeRateProvider
{
    public function getRateValues($currencies = 'USD, EUR')
    {
        $currencies = explode(',', $currencies);
        forEach($currencies as &$cur){
            $cur = trim($cur) . "RUB";
        };
        $currencies = implode(',', $currencies);
        $url = "https://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22{$currencies}%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";

        $json = file_get_contents($url);
        $result = [];

        if($json){
            $json = json_decode($json);

            $date = $json->query->created;
            forEach($json->query->results->rate as $rate){
                $rates[str_replace('RUB', '', $rate->id)] = round($rate->Rate, 4);
            }
            $cleanDate = str_replace(['Z', 'T'], ['', ' '], $date);

            $date = explode(' ', $cleanDate);
            $date[0] = explode('-', $date[0]);
            $date[1] = explode(':', $date[1]);
            $uDate = new \DateTime();
            $uDate->setDate($date[0][0], $date[0][1], $date[0][2]);
            $uDate->setTime($date[1][0], $date[1][1], $date[1][2]);

            $result['date'] = $cleanDate;
            $result['rates'] = $rates;
        }

        return $result;
    }
}
