<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m180314_064001_create_order_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
//            order_id	int	订单id
            'order_id'=>$this->integer()->comment('订单id'),
//goods_id	int	商品id
            'goods_id'=>$this->integer()->comment('商品id'),
//goods_name	varchar(255)	商品名称
            'goods_name'=>$this->string()->comment('商品名称'),
//logo	varchar(255)	图片
            'logo'=>$this->string()->comment('商品图片'),
//price	decimal	价格
            'price'=>$this->decimal()->comment('商品价格'),
//amount	int	数量
            'amount'=>$this->integer()->comment('商品数量'),
//total	decimal	小计
            'total'=>$this->decimal()->comment('小计'),
        ],'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_goods');
    }
}
