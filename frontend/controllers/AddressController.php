<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{

    public function actionIndex()
    {
        //实例化数据表
        $address = Address::find()->where(['member_id'=>\Yii::$app->user->identity->id])->all();
        return $this->render('index',['address'=>$address]);
    }

    //地址添加
    public function actionAdd(){
        //实例化活动记录
        $address = new Address();
        //实例化request组件
        $request = \Yii::$app->request;
        if ($request->isPost){
//            var_dump($request->post());exit;
            $address->load($request->post(),'');
            if ($address->validate()){

                $address->member_id = \Yii::$app->user->identity->id;
                $address->create_time = time();
                $address->save();
                return $this->redirect(['address/index']);
            }else{
                var_dump($address->getErrors());exit;
            }
        }

        //加载视图
        return $this->render('index');
    }

    //ajax修改回显
    public function actionEdit(){

        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->get('id');
        //实例化表
        $edit_address = Address::findOne(['id'=>$id])->toArray();
        //加载视图
        return json_encode($edit_address);
    }
    //修改保存
    public function actionEditSave(){
//        echo 111;exit;
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $address = Address::findOne(['id'=>$request->post('id')]);
        if ($request->isPost){
            $address->load($request->post(),'');
            if ($address->validate()){
                $res = $address->save();
                if ($res){

                }else{
                    var_dump($address->getErrors());exit;
                }
                return $this->redirect(['address/index']);
            }
        }
    }

    //删除
    public function actionDelete(){
        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->get('id');
        //实例化表单模型
        $address = Address::findOne(['id'=>$id]);
        $res = $address->delete();
        if ($res){
            return json_encode([
                'status'=>0,

            ]);
        }else{
            return json_encode([
                'status'=>1,

            ]);
        }
    }
    //修改状态
    public function actionStatus(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $address = Address::findOne(['id'=>$request->get('id')]);

        if ($address->status){
            $address->status = 0;
            $address->save(0);
//            var_dump($address->getErrors());exit;
        }else{
            $address->status = 1;
            $address->save(0);
//            var_dump($address->getErrors());exit;
        }
        return $this->redirect(['address/index']);
    }

}
