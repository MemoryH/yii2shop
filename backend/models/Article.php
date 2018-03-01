<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $is_deleted
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'article_category_id'], 'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'is_deleted', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类id',
            'sort' => '排序',
            'is_deleted' => 'Is Deleted',
            'create_time' => '创建时间',
        ];
    }

    //查询文章分类
    public static function getCategoryId(){
        //实例化文章表
        $articleCategorys = ArticleCategory::find()->where(['is_deleted'=>0])->all();
        //定义一个数组存文章的分类跟id
        $category = [];
        //循环
        foreach ($articleCategorys as $articleCategory){
            $category[$articleCategory->id]=$articleCategory->name;
        }
        return $category;
    }
    //文章分类
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}
