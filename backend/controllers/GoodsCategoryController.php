<?php

namespace backend\controllers;



use backend\models\GoodsCategory;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化分页模型
        $page = new Pagination();
        //分页总条数
        $page->totalCount = GoodsCategory::find()->count();
        //每页显示的条数
        $page->defaultPageSize = 10;
        //分页语句


        $models = GoodsCategory::find()->offset($page->offset)->limit($page->limit)->all();
//        var_dump($models);exit;
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }

    //商品分类添加
    public function actionAdd(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化模型
        $model = new GoodsCategory();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){

                    $countries = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($countries);
                }else{
                    $model->makeRoot();
                }
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                $this->redirect(['goods-category/index']);
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //加载视图
//        var_dump($nodes);exit;
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }
    //商品的修改
    public function actionEdit($id){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $model = GoodsCategory::findOne(['id'=>$id]);
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){

                    $countries = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($countries);
                }else{
                    $model->makeRoot();
                }
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                $this->redirect(['goods-category/index']);
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //加载视图
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }

    //商品分类的删除
    public function actionDelete($id){
        //通过id查询
        $model = GoodsCategory::findOne(['id'=>$id]);
        $model->delete();
        //设置提示信息
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        $this->redirect(['goods-category/index']);
    }

    //测试ztree
    public function actionZtree(){

        return $this->render('ztree');
    }
}
