<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\data\Pagination;
use yii\db\Exception;

class OrderController extends \yii\web\Controller
{
    //展示订单页面
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            $address = Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $payments = Payment::find()->all();
            $deliverys = Delivery::find()->all();
//            var_dump($carts);die;
            return $this->render('index',['address'=>$address,'carts'=>$carts,'payments'=>$payments,'deliverys' => $deliverys]);
        }

    }

    //保存订单
    public function actionSave($address,$delivery,$pay){
        //实例化表单模型
        $address = Address::findOne(['id'=>$address]);
        $delivery = Delivery::findOne(['id'=>$delivery]);
        $pay = Payment::findOne(['id'=>$pay]);
        $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
//        var_dump($carts);exit;
        if ($carts){
            $order = new Order();
            $order->member_id = \Yii::$app->user->id;
            $order->name = $address->name;
            $order->province = $address->province;
            $order->city = $address->city;
            $order->area = $address->county;
            $order->address = $address->address;
            $order->tel = $address->tel;
            $order->delivery_id = $delivery->id;
            $order->delivery_name = $delivery->delivery_name;
            $order->delivery_price = $delivery->delivery_price;
            $order->payment_id = $pay->id;
            $order->payment_name = $pay->payment_name;
            $order->total = 0;
            $order->status = 1;
            $order->trade_no = 1;
            $order->create_time = time();


            //\Yii::$app->db->createCommand($sql)->execute();
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $order->save(0);
                foreach ($carts as $cart){
                    $order_goods = new OrderGoods();
                    $order_goods->order_id = $order->id;
                    $order_goods->goods_id = $cart->goods_id;
                    $goods = Goods::findOne(['id'=>$cart->goods_id]);
                    //检查库存
                    if($goods->stock < $cart->amount){
                        //如果商品库存不足,抛出异常
                        throw new Exception('商品['.$goods->name.']库存不足');
                    }
                    //扣减商品库存
                    $goods->stock -= $cart->amount;
                    $goods->save();

                    $order_goods->goods_name = $goods->name;
                    $order_goods->logo = $goods->logo;
                    $order_goods->price = $goods->shop_price;
                    $order_goods->amount = $cart->amount;
                    $order_goods->total = $goods->shop_price*$cart->amount;
                    $order->total += $order_goods->total;
                    $order_goods->save(0);
                    //清除购物车
                    Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);


                }
                //加上送货费
                $order->total += $order->delivery_price;
                $order->save(0);
                //提交事务
                $transaction->commit();
                return $this->render('success');

            }catch (Exception $e){
                //事务回滚
                $transaction->rollBack();
            }
        }else{
            return $this->redirect(['goods/cart-index']);
        }







    }

    //显示订单列表
    public function actionList(){
        //实例化分页模型
        $pager = new Pagination();
        $pager->totalCount = Order::find()->where(['member_id'=>\Yii::$app->user->id])->count();
        $pager->defaultPageSize = 3;

        $orders = Order::find()->where(['member_id'=>\Yii::$app->user->id])->offset($pager->offset)->limit($pager->limit)->all();


        //加载视图
        return $this->render('list',['orders'=>$orders,'pager'=>$pager]);
    }
    //修改状态
    public function actionStatus($id){
        $order = Order::findOne(['id'=>$id]);
        $order->status = 0;
        $order->save(0);
        return $this->redirect(['order/list']);
    }

    //删除
    public function actionDelete($id){
        $order = Order::findOne(['id'=>$id]);
        if ($order){
            if ($order->status == 0){
                $order->delete();
                OrderGoods::deleteAll(['order_id'=>$id]);
                return 'success';
            }else{
                return 'fail';
            }
        }else{
            return 'nu';
        }
    }
}
