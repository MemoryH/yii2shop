<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180309_113129_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'tel'=>$this->integer()->notNull()->comment('联系电话'),
            'name'=>$this->string(50)->notNull()->comment('收货人姓名'),
            'province'=>$this->string(50)->notNull()->comment('省'),
            'city'=>$this->string(50)->notNull()->comment('市'),
            'county'=>$this->string(50)->notNull()->comment('县'),
            'member_id'=>$this->integer()->comment('收货人ID'),
            'status'=>$this->integer()->comment('0:正常 1:默认'),
            'address'=>$this->string()->comment('详细地址'),
            'create_time'=>$this->integer()->comment('创建时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }
}
