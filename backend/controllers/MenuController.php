<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
    //菜单列表
    public function actionIndex()
    {

        $menus = Menu::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['menus'=>$menus]);
    }
    //添加菜单
    public function actionAdd(){
        //实例化表单模型
//        $menu_category = Menu::find()->all();
        $menu = new Menu();

        //实例化manager组件
        $manager = \Yii::$app->authManager;

        //展示路由
        $address = [];
        foreach ($manager->getPermissions() as $permission){
            $address[$permission->name] = $permission->name.'['.$permission->description.']';
        }

        //实例化request组件
        $request = \Yii::$app->request;
        if ($request->isPost){
            $menu->load($request->post());
            if ($menu->validate()){
                $menu->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                return $this->redirect(['menu/index']);
            }
        }


        //加载视图页面
        return $this->render('add',['menu'=>$menu,'address'=>$address]);
    }

    //修改菜单
    public function actionEdit($id){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $menu = Menu::findOne(['id'=>$id]);
        //实例化manager组件
        $manager = \Yii::$app->authManager;
        //展示路由
        $address = [];
        foreach ($manager->getPermissions() as $permission){
            $address[$permission->name] = $permission->name.'['.$permission->description.']';
        }
        if ($request->isPost){
            $menu->load($request->post());
            if ($menu->validate()){
                $menu->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转页面
                return $this->redirect(['menu/index']);
            }
        }
        //加载视图页面
        return $this->render('add',['menu'=>$menu,'address'=>$address]);
    }

    //删除菜单
    public function actionDelete(){
        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $menu = Menu::findOne(['id'=>$id]);
        $res = $menu->delete();
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

    //过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                //默认情况对所有操作生效
            ]
        ];
    }

}
