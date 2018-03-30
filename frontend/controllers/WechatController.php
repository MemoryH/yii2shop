<?php
namespace frontend\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;

class WechatController extends Controller{
    public function actionOauth2(){

        $appid ="wxafc99f9fa69e174d";
//        $redirect_uri = url('wechat/redi','',true,true);
        $redirect_uri = Url::to('wechat/redi',true).".html";
        var_dump($redirect_uri);exit;
//        var_dump($redirect_uri);exit;
        $scope="snsapi_userinfo";

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
//        var_dump(11);exit;
        $this->redirect($url);
    }

    public function actionRedi(){
        $request = new Request();
        $appid ="wxafc99f9fa69e174d";
        $secret = "7df714c21e8a857d5d6e151a4c62a359";
        $code = $request->get('code');


        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code=$code&grant_type=authorization_code";
        $json = file_get_contents($url);
        var_dump($json);
    }
}