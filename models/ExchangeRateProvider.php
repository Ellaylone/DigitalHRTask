<?php

namespace app\models;

use Yii;

interface ExchangeRateProvider
{
    public function getRateValues($currencies = 'USD, EUR');
}
