<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;

class MemberController extends \yii\web\Controller
{
    //用户注册
    public function actionRegister(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $member = new Member();
        if ($request->isPost){
            $res = $member->load($request->post(),'');
            if ($member->validate()){
               $member->auth_key =\Yii::$app->security->generateRandomString();
               $member->created_at=time();
               $member->updated_at = time();
               $member->password_hash = \Yii::$app->security->generatePasswordHash($member->password_hash);
               $member->save(0);
                return $this->redirect(['member/login']);
            }
        }
        //加载视图
        return $this->render('register');
    }

    //验证是否重复
    public function actionVerification(){
        //实例化request组件
        $request = \Yii::$app->request;


        if ($request->get('username')){
            $username = Member::findOne(['username'=>$request->get('username')]);
            if ($username){
                return 'false';
            }else{
                return 'true';
            }

        }
        if ($request->get('email')){
            $email = Member::findOne(['email'=>$request->get('email')]);
            if ($email){
                return 'false';
            }else{
                return 'true';
            }
        }


    }

    public function actionIndex()
    {
        var_dump(\Yii::$app->user->isGuest);
        return $this->render('index');
    }

    //用户登录
    public function actionLogin(){
        //实例化登陆模型
        $login = new LoginForm();
        //实例化request组件
        $request = \Yii::$app->request;
        if ($request->isPost){
//            var_dump($request->post());exit;
            $login->load($request->post(),'');
            if($login->validate()){
                //3 验证用户信息
                if($login->login()){
                    return $this->redirect(['member/index']);
                }else{
                    echo '你密码估计输错了';exit;
                }
            }
        }
        //加载视图
        return $this->render('login',['login'=>$login]);
    }

}
