<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m180314_062354_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
//            member_id	int	用户id
            'member_id'=>$this->integer()->comment('用户id'),
//name	varchar(50)	收货人
            'name'=>$this->string(50)->comment('收货人'),
//province	varchar(20)	省
            'province'=>$this->string(20)->comment('省'),
//city	varchar(20)	市
            'city'=>$this->string(20)->comment('市'),
//area	varchar(20)	县
            'area'=>$this->string(20)->comment('县'),
//address	varchar(255)	详细地址
            'address'=>$this->string()->comment('详细地址'),
//tel	char(11)	电话号码
            'tel'=>$this->char(11)->comment('电话号码'),
//delivery_id	int	配送方式id
            'delivery_id'=>$this->integer()->comment('配送id'),
//delivery_name	varchar	配送方式名称
            'delivery_name'=>$this->string(50)->comment('配送方式名称'),
//delivery_price	float	配送方式价格
            'delivery_price'=>$this->float()->comment('配送方式价格'),
//payment_id	int	支付方式id
            'payment_id'=>$this->integer()->comment('支付方式id'),
//payment_name	varchar	支付方式名称
            'payment_name'=>$this->string(50)->comment('支付方式名称'),
//total	decimal	订单金额
            'total'=>$this->decimal()->comment('订单金额'),
//status	int	订单状态（0已取消1待付款2待发货3待收货4完成）
            'status'=>$this->integer()->comment('0已取消1待付款2待发货3待收货4完成'),
//trade_no	varchar	第三方支付交易号
            'trade_no'=>$this->string()->comment('第三方支付交易号'),
//create_time	int	创建时间
            'create_time'=>$this->integer()->comment('订单创建时间'),
        ],'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order');
    }
}
