<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180227_070244_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'article_category_id'=>$this->integer()->notNull()->comment('文章分类id'),
            'sort'=>$this->integer()->comment('排序'),
            'is_deleted'=>$this->integer(2)->notNull()->defaultValue(0),
            'create_time'=>$this->integer()->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
