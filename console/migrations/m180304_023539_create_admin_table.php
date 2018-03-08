<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180304_023539_create_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull()->defaultValue(0)->comment('基于cookie自动登录的字段'),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique()->comment('重置密码'),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('0:启用 1:停用'),
            'create_time' => $this->integer()->notNull()->defaultValue(0),
            'update_time' => $this->integer()->notNull()->defaultValue(0),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->string(20)->comment('最后登录IP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('admin');
    }
}
