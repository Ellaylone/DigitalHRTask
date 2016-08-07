<?php

namespace app\models;

use Yii;

class RatesQuery extends \yii\db\ActiveQuery
{
    public function source($source)
    {
        $this->andWhere(['source' => $source]);
        $this->orderBy(['date' => SORT_DESC]);
        return $this;
    }

    public function date($date)
    {
        $date = new \DateTime($date);
        $this->andWhere(['date' => $date->getTimestamp()]);
        return $this;
    }

    public function all($db = null)
    {
        return parent::all($db);
    }

    public function one($db = null)
    {
        return parent::one($db);
    }}
