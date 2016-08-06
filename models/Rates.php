<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

class Rates extends ActiveRecord
{
    public static function tableName()
    {
        return 'rates';
    }

    public function rules()
    {
        return [
            [['date'], 'integer'],
            [['rate'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'rate'  => 'Rate',
        ];
    }

    public function getRates()
    {
        return $this->find()->orderBy(['date' => SORT_DESC])->all();
    }

    public function getLatest()
    {
        return $this->find()->orderBy(['date' => SORT_DESC])->one();
    }

    public static function find()
    {
        return new RatesQuery(get_called_class());
    }
}
