<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;

class ArticleCategoryController extends \yii\web\Controller
{
    //展示列表页面
    public function actionIndex()
    {
        //实例化分页工具条
        $page = new Pagination();
        //获取总页数
        $page->totalCount = ArticleCategory::find()->count();
        //设置每页显示的条数
        $page->defaultPageSize = 2;
        //分页语句
        $model = ArticleCategory::find()->offset($page->offset)->where(['is_deleted'=>0])->limit($page->limit)->all();
        return $this->render('index',['models'=>$model,'page'=>$page]);
    }

    //文章分类的添加
    public function actionAdd(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $model = new ArticleCategory();
        if($request->isPost) {
            $model->load($request->post());
            //保存数据

            if ($model->validate()) {

                $model->save();
                //设置提示信息
                \yii::$app->session->setFlash('success', '添加成功');
                //跳转页面
                $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //加载显示视图
        return $this->render('add',['model'=>$model]);
    }

    //文章的修改
    public function actionEdit($id){
        //实例化request组件
        $request = \Yii::$app->request;
        //通过id查询数据
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($request->isPost) {
            $model->load($request->post());
            //保存数据


            if ($model->validate()) {
                $model->save();
                //设置提示信息
                \yii::$app->session->setFlash('success', '添加成功');
                //跳转页面
                $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //文章的删除
    public function actionDelete($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $model->save();
        //提示跳转信息
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转页面
        $this->redirect(['article-category/index']);
    }

}
