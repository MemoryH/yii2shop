<?php

namespace backend\controllers;

use app\models\Brand;
use backend\filters\RbacFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends \yii\web\Controller
{

    //解决防跨站攻击
    public $enableCsrfValidation = false;
    //展示列表功能
    public function actionIndex()
    {
//        var_dump($this->uniqueId);exit;
        //实例化分页工具条
        $page = new Pagination();
        $page->totalCount = Brand::find()->count();
        $page->defaultPageSize = 10;

        //实例化活动记录
        $brands = Brand::find()->offset($page->offset)->where(['is_deleted'=>0])->limit($page->limit)->all();

        return $this->render('index',['brands'=>$brands,'page'=>$page]);
    }

    //品牌添加功能
    public function actionAdd(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $model = new Brand();
        if($request->isPost) {
            $model->load($request->post());
            //保存数据



            if ($model->validate()) {

                $model->save();
                //设置提示信息
                \yii::$app->session->setFlash('success', '添加成功');
                //跳转页面
                $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //加载显示视图
        return $this->render('add',['model'=>$model]);
    }

    //品牌修改功能
    public function actionEdit($id){
        //实例化request组件
        $request = \Yii::$app->request;
        //通过id查询数据
        $model = Brand::findOne(['id'=>$id]);
        if($request->isPost) {
            $model->load($request->post());
            //保存数据


            if ($model->validate()) {
                $model->save();
                //设置提示信息
                \yii::$app->session->setFlash('success', '添加成功');
                //跳转页面
                $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    //品牌的删除
    public function actionDelete(){
//实例化request组件
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $model = Brand::findOne(['id'=>$id]);
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

    //处理上传文件
    public function actionLogo(){
        //实例化上传文件类
        $uploadFile = UploadedFile::getInstanceByName('file');
        $file = 'upload/' . date('Y-m-d');

        if (!is_dir($file)) {
//            var_dump($file);exit;
            mkdir($file, 0777, true);
        }
        $file = $file . '/' . uniqid() . '.' . $uploadFile->extension;
        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot') .'/'. $file, 0);
        if ($result){
            //文件保存成功 返回文件路径
            $accessKey ="TxMyeDQ095vC5DtBNrUmE_PqD-Ds6I1mz3i__KJk";
            $secretKey = "M69Cr5LgZCbmJrdmxqRfDl2qvdPYiZB8nZ6P9F7g";
            $bucket = "yii2shop";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').'/'.$file;
            // 上传到七牛后保存的文件名
            $key = '/'.$file;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//        echo "\n====> putFile result: \n";
            if ($err == null) {
                return json_encode([
                    'url'=>"http://p4w1z299s.bkt.clouddn.com/{$key}"
                ]);
            } else {
                var_dump($err);
            }
//            return json_encode([
//                'url'=>$file
//            ]);
        }


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
            ]
        ];
    }
}
