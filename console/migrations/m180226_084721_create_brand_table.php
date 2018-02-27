<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180226_084721_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull()->comment('品牌名称'),
            'intro'=>$this->text()->comment('简介'),
            'logo'=>$this->string(255)->notNull()->comment('LOGO'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'is_deleted'=>$this->integer(1)->notNull()->defaultValue(0)->comment('状态 0:正常 1:删除')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
