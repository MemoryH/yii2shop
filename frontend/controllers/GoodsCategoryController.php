<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化表单模型
        $goods_category = GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['goods_category'=>$goods_category]);
    }

}
