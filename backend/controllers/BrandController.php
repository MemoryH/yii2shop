<?php

namespace backend\controllers;

use app\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{

    //解决防跨站攻击
    public $enableCsrfValidation = false;
    //展示列表功能
    public function actionIndex()
    {
        //实例化分页工具条
        $page = new Pagination();
        $page->totalCount = Brand::find()->count();
        $page->defaultPageSize = 2;

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
    public function actionDelete($id){
        $model = Brand::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $model->save();
        //提示跳转信息
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转页面
        $this->redirect(['brand/index']);
    }

    //处理上传文件
    public function actionLogo(){
        //实例化上传文件类
        $uploadFile = UploadedFile::getInstanceByName('file');
        $file = '/upload/' . date('Y-m-d');
        if (!is_dir($file)) {
            mkdir($file, 0777, true);
        }
        $file = $file . '/' . uniqid() . '.' . $uploadFile->extension;
        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot') .'/'. $file, 0);
        if ($result){
            //文件保存成功 返回文件路径
            return json_encode([
                'url'=>$file
            ]);

        }


    }

}
