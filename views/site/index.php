<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */

$this->title = 'Rates';
Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false, 'id' => 'ratesPjax', 'clientOptions' => ['maxCacheLength' => 9999]]);
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2>Курс</h2>
                    <?php
                    if($latestRate){
                        echo Html::tag('small', "Источник: {$latestRate->source}");
                        echo Html::tag('p', $latestRate->date);
                        foreach($latestRate->rate as $key => $rate){
                            echo Html::ul([$key, number_format($rate, 4)]);
                        }
                    } else {
                        echo Html::tag('p', 'Нет данных');
                    }
                    ?>
                <?=Html::a('Обновить', Url::current(), ['class' => 'btn btn-default refreshRates']);?>
                <?=Html::a('Получить новые данные', Url::to(['site/update']), ['data-pjax' => 0, 'class' => 'btn btn-default updateRates']);?>
            </div>
        </div>
    </div>
</div>
<?php
Pjax::end();
