<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    //文章列表功能
    public function actionIndex()
    {
        //实例化分页工具
        $page = new Pagination();
        //分页的总条数
        $page->totalCount = Article::find()->count();
        //每页显示的条数
        $page->defaultPageSize = 2;
        //分页语句
        $articles = Article::find()->offset($page->offset)->where(['is_deleted'=>0])->limit($page->limit)->all();
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }
    //文章添加
    public function actionAdd(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $model = new Article();
        $content = new ArticleDetail();
        //判断接收数据完成添加
        if($request->isPost){
            $model->load($request->post());
            $content->load($request->post());
            //判断是否加载成功
            if ($model->validate()&&$content->validate()){
                $model->create_time = time();
                $model->save();
                $article_id = $model->attributes['id'];
//                var_dump($article_id);exit;
                //设置添加时间

                $content->article_id = $article_id;

                $content->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                $this->redirect(['article/index']);
            }
        }
        //加载文章显示视图
        return $this->render('add',['model'=>$model,'content'=>$content]);
    }

    //文章修改
    public function actionEdit($id){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化文章表
        $model = Article::findOne(['id'=>$id]);
        $content = ArticleDetail::findOne(['article_id'=>$id]);
        //判断接收数据完成添加
        if($request->isPost){
            $model->load($request->post());
            $content->load($request->post());
            //判断是否加载成功
            if ($model->validate()&&$content->validate()){
                $model->save();
                $article_id = $model->attributes['id'];
//                var_dump($article_id);exit;
                //设置添加时间

                $content->article_id = $article_id;

                $content->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                $this->redirect(['article/index']);
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model,'content'=>$content]);
    }

    //删除页面
    public function actionDelete($id){
        $model = Article::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $model->save();
        //提示跳转信息
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转页面
        $this->redirect(['article/index']);
    }

    //查看详细内容
    public function actionShow($id){
        //实例化文章模型
        $article = Article::findOne($id);
        //实例化文章内容模型
        $content = ArticleDetail::findOne(['article_id'=>$id]);
        //加载视图
        return $this->render('show',['article'=>$article,'content'=>$content]);
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
        ]
    ];
}

}
