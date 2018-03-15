<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Brand;
use frontend\models\Cart;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $goods = Goods::findOne(['id'=>$id]);
        $goods->view_times = $goods->view_times+1;
        $goods->save(0);
        $goods_photo = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $goods_intro = GoodsIntro::findOne(['goods_id'=>$id]);
        $brand =Brand::findOne(['id'=>$goods->brand_id]);
        return $this->render('index',['goods'=>$goods,'goods_photo'=>$goods_photo,'goods_intro'=>$goods_intro,'brand'=>$brand]);
    }


    //加入购物车
    public function actionAddCart($goods_id,$amount){

        //判断是否登录
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            //判断cookie里是否存在此商品
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //如果购物车存在该商品,则该商品的数量累加
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id]=$amount;
            }

            //将购物车数据保存到cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire = 7*24*3600+time();
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);

        }else{
            //如果登录则保存到数据表中
            //实例化request组件
            $request = \Yii::$app->request;
            //实例化数据表
            $model = new Cart();
            $carts = Cart::findOne(['goods_id'=>$goods_id]);
            if ($carts){
                $carts->amount = $carts->amount+$amount;
                $carts->save(0);

            }else{
                $model->load($request->get(),'');
                if ($model->validate()){
                    $model->member_id = \Yii::$app->user->id;
                    $model->save();
                }
            }

        }

        //加载视图页面
        return $this->render('add-success');
    }

    //购物车页面
    public function actionCartIndex(){

        if (\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
        }
        else{
            $cartss = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all();
//            var_dump($carts);exit;
            $carts = [];
            foreach ($cartss as $cart){
                $carts[$cart['goods_id']]=$cart['amount'];
            }

        }
        //加载视图
        return $this->render('carts',['carts'=>$carts]);
    }
    //修改购物车
    public function actionEdit($goods_id,$amount){

        if (\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            //判断cookie里是否存在此商品
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //如果购物车存在该商品,则改变改商品
            if($amount){
                $carts[$goods_id] = $amount;
            }else{
                //如果没有该商品则删除该商品
                unset($carts[$goods_id]);
                $cookie = new Cookie();
                $cookie->name = 'carts';
                $cookie->value = serialize($carts);
                $cookie->expire = 7*24*3600+time();
                $cookies = \Yii::$app->response->cookies;
                $cookies->add($cookie);
                return 'success';
            }

            //将购物车数据保存到cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire = 7*24*3600+time();
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{
            //实例化request组件
            $request = \Yii::$app->request;
            //实例化数据表
//            $model = new Cart();
            $carts = Cart::findOne(['goods_id'=>$goods_id]);
            if ($amount){
                $carts->amount = $amount;
                $carts->save(0);

            }else{
                $carts->delete();
                return 'success';
            }

        }
    }

}
