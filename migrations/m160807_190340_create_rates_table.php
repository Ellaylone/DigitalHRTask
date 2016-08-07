<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `rates`.
 */
class m160807_190340_create_rates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('rates', [
            'id' => Schema::TYPE_PK,
            'date' => Schema::TYPE_INTEGER,
            'rate' => Schema::TYPE_STRING,
            'source' => Schema::TYPE_STRING,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('rates');
    }
}
