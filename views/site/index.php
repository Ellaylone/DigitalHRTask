<?php

/* @var $this yii\web\View */

$this->title = 'Rates';
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
