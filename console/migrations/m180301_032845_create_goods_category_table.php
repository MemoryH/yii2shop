<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m180301_032845_create_goods_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
//            tree	int()	树id
            'tree'=>$this->integer()->defaultValue(0)->comment('树id'),
//lft	int()	左值
            'lft'=>$this->integer()->comment('左值'),
//rgt	int()	右值
            'rgt'=>$this->integer()->comment('右值'),
//depth	int()	层级
            'depth'=>$this->integer()->comment('层级'),
//name	varchar(50)	名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
//parent_id	int()	上级分类id
            'parent_id'=>$this->integer()->notNull()->comment('上级分类'),
//intro	text()	简介
            'intro'=>$this->text()->comment('简介')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_category');
    }
}
