<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\MemberApi;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller{


    public $enableCsrfValidation = false;
//初始化
    public function init(){
        parent::init();
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }
    //用户登录
    public function actionLogin(){

        //实例化request组件
        $request = \Yii::$app->request;
        if ($request->isPost){
//            var_dump($request->post());exit;
            $member = Member::findOne(['username'=>$request->post('username')]);
            if ($member){
                if(\Yii::$app->security->validatePassword($request->post('password'),$member->password_hash)){
                    //最后登录时间
                    $member->last_login_time = time();
                    //最后登录IP
                    $member->last_login_ip =ip2long($_SERVER['SERVER_ADDR']);
                    $res = $member->save(0);
                    //密码一致,登录成功 保存用户信息到session
                    $cookie = !empty($this->remember)?3600*24*7:0;
//                    var_dump($member);exit;
                    \Yii::$app->user->login($member,$cookie);
                    $result = [
                        'error_code'=>0,
                        'msg'=>'登录成功',
                        'data'=>$member
                    ];
                    return $result;
                }else{
                    $result = [
                        'error_code'=>1,
                        'msg'=>'用户名或密码错误',

                    ];
                    return $result;
                }
            }else{
                $result = [
                    'error_code'=>1,
                    'msg'=>'用户名或密码错误'
                ];
                return $result;
            }

        }else{
            ///返回请求方式有误
           $result = [
                    'error_code'=>2,
                    'msg'=>'请求方式有误'
                ];
                return $result;
        }


    }

    //用户注册
    public function actionRegister(){
      //实例化request组件
        $request = \Yii::$app->request;
        //实例化活动记录
        $register = new MemberApi();
        if ($request->isPost){
            $register->load($request->post(),'');
            if ($register->validate()){
                $register->auth_key =\Yii::$app->security->generateRandomString();
                $register->created_at=time();
                $register->updated_at = time();
                $register->password_hash = \Yii::$app->security->generatePasswordHash($register->password_hash);
                $register->save(0);
                $result = [
                    'error_code'=>0,
                    'msg'=>'注册成功',
                    'data'=>$register
                ];
                return $result;
            }
        }else{
            $result = [
                'error_code'=>2,
                'msg'=>'请求方式有误'
            ];
            return $result;
        }
    }

    //修改密码
    public function actionEditPassword(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $member = MemberApi::findOne(['id'=>$request->post('id')]);
        if (\Yii::$app->security->validatePassword($request->post('old_password'),$member->password_hash)){
            $member->password_hash =\Yii::$app->security->generatePasswordHash($request->post('password'));
            $res = $member->save(0);
            if ($res){
                $result = [
                    'error_code'=>0,
                    'msg'=>'修改密码成功'
                ];
                  return $result;
            }else{
                $result = [
                    'error_code'=>2,
                    'msg'=>$member->getErrors()
                ];
                return $result;
            }

        }else{
            $result = [
                'error_code'=>1,
                'msg'=>'旧密码不正确'
            ];
            return $result;
        }
    }

    //添加收货地址
    public function actionAddAddress(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化活动记录
        $address = new Address();

    }
}