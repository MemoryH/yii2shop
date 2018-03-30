<?php



namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\data\Pagination;

class ListController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化request组件
        $request= \Yii::$app->request;
        $parent_id = $request->get('id')??'';
        $cate = GoodsCategory::findOne(['id'=>$parent_id]);
        //处理分类不存在的情况
//        var_dump($cate);exit;
        switch ($cate->depth){
            case 0://1级分类
            case 1://2级分类
                $ids = $cate->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->column();
                break;
            case 2://3级分类
                $ids = [$parent_id];
                break;
        }
        //一级分类
//        var_dump($ids);exit;
        //实例化分页模型
        $pager = new Pagination();
        $pager->totalCount = Goods::find()->where(['in','goods_category_id',$ids])->count();
        $pager->defaultPageSize = 4;
        $goods = Goods::find()->where(['in','goods_category_id',$ids])->andWhere(['status'=>1])->offset($pager->offset)->limit($pager->limit)->all();
        //实例化表单模型
//        var_dump($ids);exit;
//        $goods = Goods::find()->where(['goods_category_id'=>$parent_id])->andWhere(['status'=>1])->all();
//        var_dump($pager);exit;
        return $this->render('index',['goods'=>$goods,'pager'=>$pager]);
    }

}
