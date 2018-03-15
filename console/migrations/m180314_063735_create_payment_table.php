<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m180314_063735_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'payment_name'=>$this->string(50)->comment('支付方式名称'),
            'payment_intro'=>$this->string()->comment('支付方式详情'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment');
    }
}
