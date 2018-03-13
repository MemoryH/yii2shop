<?php

namespace frontend\controllers;

use frontend\aliyun\SignatureHelper;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Url;

class MemberController extends \yii\web\Controller
{
    //用户注册
    public function actionRegister(){
//        var_dump(Url::to(['member/register']));exit;
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $member = new Member();
        if ($request->isPost){

            $res = $member->load($request->post(),'');
            if ($member->validate()){
//                var_dump($member->code);exit;
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
        if ($request->get('tel')&&$request->get('code')){
            $tel = $request->get('tel');
            $code = $request->get('code');
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $really = $redis->get('code_'.$tel);
//            var_dump($really);
            if ($really ==$code){
                return 'true';
            }else{
                return 'false';
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
                    $cookies = \Yii::$app->request->cookies;
                    $value = $cookies->getValue('carts');
                    if($value){
                        $carts = unserialize($value);
                        foreach ($carts as $goods_id=>$amount){
                            $model = new Cart();
                            $cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
                            if ($cart){
                                $cart->amount = $cart->amount+$amount;
                                $cart->save(0);
                            }else{
                                $model->goods_id=$goods_id;
                                $model->amount = $amount;
                                $model->member_id = \Yii::$app->user->id;
                                if ($model->save(0)){

                                }else{
                                    var_dump($model->getErrors());exit;
                                }
                            }
                        }
                        $cookie = \Yii::$app->response->cookies;
                        $cookie->remove('carts');
                    }else{
                        $carts = [];
                    }
                    return $this->redirect(['goods-category/index']);
                }else{
                    echo '你密码估计输错了';exit;
                }
            }
        }
        //加载视图
        return $this->render('login',['login'=>$login]);
    }

    //获取短信验证码
    public function actionVerify(){
        $code = rand(100000,999999);
        //实例化request组件
        $request = \Yii::$app->request;
        $tel = $request->get('tel');
        $res=  Member::SendSms($tel,$code);
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('code_'.$tel,$code,5*60);
        if ($res){
            return 'success';
        }else{
            return 'fail';
        }
    }

    //用户注销
    public function actionLogout(){
        //注销登录
        \Yii::$app->user->logout();
        return $this->redirect(['goods-category/index']);
    }

    public function actionTest(){
       $res=  Member::SendSms('18780975775','bufulaidawo');
       var_dump($res);
    }
    public function actionRedis(){
         phpinfo();
    }
}
