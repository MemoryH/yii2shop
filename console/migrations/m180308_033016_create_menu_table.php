<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m180308_033016_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('菜单名称'),
            'parent_id'=>$this->integer()->notNull()->comment('上级菜单id'),
            'address'=>$this->string(50)->notNull()->comment('路由/地址'),
            'sort'=>$this->integer()->comment('排序'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('menu');
    }
}
