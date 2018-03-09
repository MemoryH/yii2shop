<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\EditPasswordForm;
use yii\data\Pagination;
use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{
    //解决防跨站攻击
    public $enableCsrfValidation = false;
    //列表页面
    public function actionIndex()
    {

//        var_dump($_COOKIE);exit;

        //实例化分页工具
        $page = new Pagination();
        //分页的总条数
        $page->totalCount = Admin::find()->count();
        //每页显示的条数
        $page->defaultPageSize = 10;
        //分页语句
        $admins = Admin::find()->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['admins'=>$admins,'page'=>$page]);

    }
    //添加功能
    public function actionAdd(){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $admin = new Admin();
        //指定场景
        $admin->scenario = Admin::SCENARIO_ADD;
        //实例化manager组件
        $manager = \Yii::$app->authManager;
        //查询角色信息
        $roles = $manager->getRoles();
//        var_dump($roles);exit;
        $roleArr = [];
        foreach ($roles as $role){
            $roleArr[$role->name]=$role->name;
        }
        //通过提交方式判断接收数据
        if ($request->isPost){
                $admin->load($request->post());


                if ($admin->validate()){
                    //给密码加密
                    $admin->password_hash =\Yii::$app->security->generatePasswordHash($admin->password_hash);
                    $admin->create_time = time();
                    $admin->update_time = time();
                    $admin->auth_key = \Yii::$app->security->generateRandomString();

                    $res = $admin->save(0);
                    //循环为用户添加角色
                    foreach ($admin->role as $role){
                        $role = $manager->getRole($role);
                        $manager->assign($role,$admin->id);
                    }
                    if ($res){
                        //设置提示信息
                        \Yii::$app->session->setFlash('success','添加成功');
                        //跳转页面
                        return $this->redirect(['admin/index']);
                    }else{
                        var_dump($admin->getErrors());exit;
                    }


                }
        }

        //加载视图页面
        return $this->render('add',['admin'=>$admin,'roleArr'=>$roleArr]);
    }

    //修改用户表
    public function actionEdit($id){
        //实例化request组件
        $request = \Yii::$app->request;

        //实例化表单模型
        $admin = Admin::findOne(['id'=>$id]);
        //实例化manager组件
        $manager = \Yii::$app->authManager;
        //查询角色信息
        $roles = $manager->getRoles();
//        var_dump($roles);exit;
        $roleArr = [];
        foreach ($roles as $role){
            $roleArr[$role->name]=$role->name;
        }
        $user_roles = $manager->getRolesByUser($id);
//        var_dump($user_role);exit;

        if (is_array($user_roles)){
            foreach ($user_roles as $user_role){
                $admin->role[]=$user_role->name;
            }
        }

        if ($request->isPost){
//            echo 111;die;
            $admin->load($request->post());

            if ($admin->validate()){
                $admin->update_time = time();
//              删除之前的角色
                foreach ($user_roles as $user_role){
                    $manager->revoke($user_role,$id);
                }
                $res = $admin->save(0);
                //重新保存修改之后的角色
                if (is_array($admin->role)){
                    foreach ($admin->role as $role){
                        $role = $manager->getRole($role);
                        $manager->assign($role,$id);
                    }
                }

//                var_dump($res);die;
                if ($res){
                    //设置提示信息
                    \Yii::$app->session->setFlash('success','修改成功');
                    //跳转页面
                    return $this->redirect(['admin/index']);
                }else{
                    var_dump($admin->getErrors());exit;
                }
            }else{
                var_dump($admin->getErrors());exit;
            }
        }
        //加载视图页面
        return $this->render('add',['admin'=>$admin,'roleArr'=>$roleArr]);
    }

    //删除用户表
    public function actionDelete(){
        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->post('id');
        //实例化数据表
        $admin = Admin::findOne(['id'=>$id]);
        $res = $admin->delete();
        $manager = \Yii::$app->authManager;
        $user_roles = $manager->getRolesByUser($id);
//        删除用户的角色关系
                foreach ($user_roles as $user_role){
                    $manager->revoke($user_role,$id);
                }
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

    //修改密码
    public function actionPassword($id){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化数据表
        $admin = \Yii::$app->user->identity;
        $password = new EditPasswordForm();
//        var_dump($password);exit;
        if ($request->isPost){
            $password->load($request->post());
            if ($password->validate()){
                if (\Yii::$app->security->validatePassword($password->old,$admin->password_hash)){
                    $admin->password_hash = \Yii::$app->security->generatePasswordHash($password->password);
                    $admin->save();
                    //设置提示信息
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('warning','修改密码成功请重新登录');
                    //跳转
                    return $this->redirect(['login/login']);
                }else{
                    $password->addError('old','密码错误');
                }
            }
        }

        //加载视图模型
        return $this->render('password',['password'=>$password,'admin'=>$admin]);
    }
//    public function actionTest(){
//        $id = \Yii::$app->user->id;
//        var_dump($id);exit;
//    }
//过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                //默认情况对所有操作生效
                'except'=>['password']
            ],

        ];
    }


}
