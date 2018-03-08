<?php

namespace backend\controllers;

use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\web\Controller;

class LoginController extends Controller{
    public function actionLogin(){
        //1 登录表单
        $model = new LoginForm();
        //2 接受数据
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //3 验证用户信息
                if($model->login()){

                    \Yii::$app->session->setFlash('success','登录成功');


                    return $this->redirect(['admin/index']);
                }
            }
        }
        //4 登录成功后跳转
        return $this->render('index',['model'=>$model]);
    }


    //注销
    public function actionLogout(){
        //注销登录
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['login/login']);
    }

    //验证码
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>4,
                'maxLength'=>6,
            ]
        ];
    }


}