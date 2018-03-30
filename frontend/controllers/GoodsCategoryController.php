<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\SphinxClient;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化表单模型
        $goods_category = GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['goods_category'=>$goods_category]);
    }

    public function actionSearch($info){
//        require ( "sphinxapi.php" );
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
        $cl->SetLimits(0, 1000);
//        $info = '戴尔';
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
//        print_r($res);
//        var_dump($res);exit;
        if (isset($res)){
            $ids=[];
            foreach ($res['matches'] as $r){
                $ids[] = $r['id'];
            }
            $pager = new Pagination();
            $pager->totalCount = Goods::find()->where(['in','id',$ids])->count();
            $pager->defaultPageSize = 4;
            $goods = Goods::find()->where(['in','id',$ids])->andWhere(['status'=>1])->offset($pager->offset)->limit($pager->limit)->all();
            //实例化表单模型
//        var_dump($ids);exit;
//        $goods = Goods::find()->where(['goods_category_id'=>$parent_id])->andWhere(['status'=>1])->all();
//        var_dump($pager);exit;
            return $this->render('/list/index',['goods'=>$goods,'pager'=>$pager]);


        }else{

        }
    }

}
