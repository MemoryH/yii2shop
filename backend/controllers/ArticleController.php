<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
use yii\filters\AccessControl;

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
        $page->defaultPageSize = 10;
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
    public function actionDelete(){
        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $model = Article::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $res = $model->save();
        if ($res){
            return json_encode([
                'status'=>0,

            ]);
        }else{
            return json_encode([
                'status'=>1,

            ]);
        }
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
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
            ],
        ]
    ];
}

//测试七牛云
    public function actionText(){

// 需要填写你的 Access Key 和 Secret Key
        $accessKey ="TxMyeDQ095vC5DtBNrUmE_PqD-Ds6I1mz3i__KJk";
        $secretKey = "M69Cr5LgZCbmJrdmxqRfDl2qvdPYiZB8nZ6P9F7g";
        $bucket = "yii2shop";
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = \Yii::getAlias('@webroot').'/upload/image/20180227/1.jpg';
        // 上传到七牛后保存的文件名
        $key = '/upload/20180227/1.jpg';
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
            }

    //过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                //默认情况对所有操作生效
                'except'=>['upload']
            ],

        ];
    }


}
