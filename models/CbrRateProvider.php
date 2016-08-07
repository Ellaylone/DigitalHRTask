<?php

namespace app\models;

use Yii;
use app\models\Rates;

class CbrRateProvider implements ExchangeRateProvider
{
    public function getRateValues($currencies = 'USD, EUR')
    {
        $curId = ['USD' => 'R01235', 'EUR' => 'R01239'];
        $currencies = explode(',', $currencies);
        $xmlRates = [];
        $result = [];
        $xmlDate = new \DateTime();
        $today = $xmlDate->format('d/m/Y');
        $xmlDate->sub(new \DateInterval('P2D'));
        $prevday = $xmlDate->format('d/m/Y');
        forEach($currencies as &$cur){
            $cur = trim($cur);
            $url = "http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1={$prevday}&date_req2={$today}&VAL_NM_RQ={$curId[$cur]}";
            $xml = file_get_contents($url);

            $r = array();
            $reg = "/<ValCurs.*?>(.*?)<\/ValCurs>/is";

            if(preg_match($reg, $xml, $m)){
                preg_match_all("/<Record(.*?)>(.*?)<\/Record>/is", $m[1], $r, PREG_SET_ORDER);
            }
            $m = array();
            $d = array();

            for($i=0; $i<count($r); $i++) {
                if(preg_match("/Date=\"(\d{2})\.(\d{2})\.(\d{4})\"/is", $r[$i][1],$m)) {
                    $dv = "{$m[1]}/{$m[2]}/{$m[3]}";
                    if(preg_match("/<Nominal>(.*?)<\/Nominal>.*?<Value>(.*?)<\/Value>/is", $r[$i][2], $m)) {
                        $m[2] = preg_replace("/,/",".",$m[2]);
                        $d[] = array($dv, $m[1], $m[2]);
                    }
                }
            }
            $temp = array_pop($d);
            $result['date'] = $temp[0];
            $result['rates'][$cur] = round($temp[2], 4);
        };

        $tempDate = explode('/', $result['date']);
        $result['date'] = new \DateTime();
        $result['date']->setDate($tempDate[2], $tempDate[1], $tempDate[0]);
        $result['date'] = $result['date']->format('Y-m-d H:i:s');

        return $result;
    }
}
