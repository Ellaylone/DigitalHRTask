<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Rates';
Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false, 'id' => 'ratesPjax']);
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <?php
                if($latestRate){
                    echo "<p>";
                    echo $latestRate->date . " - " . $latestRate->rate;
                    echo "</p>";
                    echo Html::a('Refresh', Url::current());
                } else {
                    echo "<p>";
                    echo "Нет данных";
                    echo "</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
Pjax::end();
