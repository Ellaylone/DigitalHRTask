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

    public function beforeValidate(){
        $this->rate = json_encode($this->rate);

        $date = explode(' ', $this->date);
        $date[0] = explode('-', $date[0]);
        $date[1] = explode(':', $date[1]);
        $uDate = new \DateTime();
        $uDate->setDate($date[0][0], $date[0][1], $date[0][2]);
        $uDate->setTime($date[1][0], $date[1][1], $date[1][2]);
        $this->date = $uDate->getTimestamp();
        return true;
    }

    public function afterFind()
    {
        parent::afterFind();

        $date = new \DateTime();
        $date->setTimestamp($this->date);
        $this->date = $date->format('Y-m-d H:i:s');
        $this->rate = json_decode($this->rate, true);
    }
}
