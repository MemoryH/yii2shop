<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $captcha;
    public $remember;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            [['remember'],'safe'],
            ['captcha','captcha','captchaAction'=>'site/captcha'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
//            'verifyCode' => '验证码',
        ];
    }

    public function login(){
        //根据用户名查询出密码,在和传过来的密码比较
        $member = Member::findOne(['username'=>$this->username]);
                if ($member){
                    if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                        //最后登录时间
                        $member->last_login_time = time();
                        //最后登录IP
                        $member->last_login_ip =ip2long($_SERVER['SERVER_ADDR']);
                        $res = $member->save(0);
                        //密码一致,登录成功 保存用户信息到session
                        $cookie = !empty($this->remember)?3600*24*7:0;
//                    var_dump($member);exit;
                        return \Yii::$app->user->login($member,$cookie);
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }



        return false;
    }
}