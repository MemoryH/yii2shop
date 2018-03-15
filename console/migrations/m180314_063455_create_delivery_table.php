<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m180314_063455_create_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('delivery', [
            'id' => $this->primaryKey(),
            'delivery_name'=>$this->string(50)->comment('配送方式名称'),
//delivery_price	float	配送方式价格
            'delivery_price'=>$this->float()->comment('配送方式价格'),
            'delivery_intro'=>$this->string()->comment('配送方式详情'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('delivery');
    }
}
