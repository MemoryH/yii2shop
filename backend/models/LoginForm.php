<?php
namespace backend\models;
use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $verifyCode;
    public $remember;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            [['remember'],'safe'],
            ['verifyCode','captcha','captchaAction'=>'login/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'verifyCode' => '验证码',
        ];
    }

    public function login(){
        //根据用户名查询出密码,在和传过来的密码比较
        $admin = Admin::findOne(['username'=>$this->username]);
        if($admin){
            if ($admin->status ==0){
                //用户名存在比较密码是否一致
                if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                    //最后登录时间
                    $admin->last_login_time = time();
                    //最后登录IP
                    $admin->last_login_ip = $_SERVER['SERVER_ADDR'];
//                var_dump($_SERVER);exit;
                    $admin->save();
//                var_dump($this->remember);exit;
                    //密码一致,登录成功 保存用户信息到session
                    $cookie = !empty($this->remember)?3600*24*7:0;
                    return \Yii::$app->user->login($admin,$cookie);
                }else{
                    //密码不一致,登录失败 添加错误提示
                    $this->addError('password','密码错误');
                }
            }else{
                $this->addError('username','用户被禁用');
            }

        }else{
            //用户名不存在
            $this->addError('username','用户名不存在');
        }
        return false;
    }
}