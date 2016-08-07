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
        $url = 'https://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22' . $currencies . '%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        if($result){
            var_dump($result);
            $this->json = $result;
            $this->saveRateValues();
        }
    }
    public function saveRateValues()
    {
        $date = '';
        $rates = [];
        $source = "Yahoo";
        $this->json = json_decode($this->json);

        $date = $this->json->query->created;
        forEach($this->json->query->results->rate as $rate){
            $rates[str_replace('RUB', '', $rate->id)] = round($rate->Rate, 4);
        }
        $cleanDate = str_replace(['Z', 'T'], ['', ' '], $date);

        $date = explode(' ', $cleanDate);
        $date[0] = explode('-', $date[0]);
        $date[1] = explode(':', $date[1]);
        $uDate = new \DateTime();
        $uDate->setDate($date[0][0], $date[0][1], $date[0][2]);
        $uDate->setTime($date[1][0], $date[1][1], $date[1][2]);

        $model = new Rates();
        $query = $model->find()->source($source)->andWhere(['date' => $uDate->getTimestamp()])->one();
        if($query){
            $query->rate = $rates;
            $query->update();
        } else {
            $model->id = 0;
            $model->rate = $rates;
            $model->date = $cleanDate;
            $model->source = $source;
            $model->save();
        }
    }
}
