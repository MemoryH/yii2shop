<?php
namespace frontend\controllers;

use yii\helpers\Url;
use yii\web\Controller;

class WechatController extends Controller{
    public function actionOauth2(){

        $appid ="wxafc99f9fa69e174d";
//        $redirect_uri = url('wechat/redi','',true,true);
        $redirect_uri = Url::to('wechat/redi',true);
//        var_dump($redirect_uri);exit;
//        var_dump($redirect_uri);exit;
        $scope="snsapi_userinfo";

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
//        var_dump(11);exit;
        $this->redirect($url);
    }

    public function redi(){
        $code = request()->get('code');
        var_dump($code);
    }
}