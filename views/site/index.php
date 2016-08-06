<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Rates';
Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false, 'id' => 'ratesPjax', 'clientOptions' => ['maxCacheLength' => 9999]]);
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <p>
                <?php
                if($latestRate){
                    echo $latestRate->date . " - " . $latestRate->rate;
                } else {
                    echo "Нет данных";
                }
                ?>
                </p>
                <?=Html::a('Refresh', Url::current());?>
            </div>
        </div>
    </div>
</div>
<?php
Pjax::end();
