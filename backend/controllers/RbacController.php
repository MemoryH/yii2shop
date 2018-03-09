<?php
/**
 * Created by PhpStorm.
 * User: 悦悦
 * Date: 2018/3/7 0007
 * Time: 14:01
 */

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Controller;

class RbacController extends Controller
{
    //添加权限
    public function actionAddPermission(){
        //实例化request组件
        $request  = \Yii::$app->request;
        $model = new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        //实例化manager组件
        $manager = \Yii::$app->authManager;

        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){

                    $permisson = $manager->createPermission($model->name);
                    $permisson->description = $model->description;
                    $res = $manager->add($permisson);
                    if ($res){
                        //设置提示信息
                        \Yii::$app->session->setFlash('success','设置权限成功');
                        //跳转页面
                        return $this->redirect(['rbac/index']);

                }

            }



        }
        return $this->render('add-permission',['model'=>$model]);
    }

    //权限列表
    public function actionIndex(){
        $manager = \Yii::$app->authManager;
        $permission = $manager->getPermissions();
        return $this->render('index',['permissions'=>$permission]);
    }
    //修改权限
    public function actionEditPermission($name){
        //实例化manager组件
        $manager = \Yii::$app->authManager;
        //实例化request组件
        $request = \Yii::$app->request;
        $permission = $manager->getPermission($name);
        if($permission==null){
            throw new HttpException(404,'权限不存在');
        }

        $model = new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_EDIT;
        $model->name = $permission->name;
        $model->description = $permission->description;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $permission->name = $model->name;
                $permission->description = $model->description;
                $res = $manager->update($name,$permission);
//                var_dump($res);exit;
                if ($res){
                    //设置提示信息
                    \Yii::$app->session->setFlash('success','设置权限成功');
                    //跳转页面
                    return $this->redirect(['rbac/index']);
                }
            }
        }
        //加载视图
        return $this->render('add-permission',['model'=>$model]);

    }
    //删除权限
    public function actionDeletePermission(){
        //实例化request组件
        $request = \Yii::$app->request;
        $name = $request->post('name');
        //实例化manager组件
        $manager = \Yii::$app->authManager;
        $permission = $manager->getPermission($name);
        $res = $manager->remove($permission);
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



    //角色的添加
    public function actionAddRole(){
        //实例化manager组件
        $manager = \Yii::$app->authManager;


        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $model = new RoleForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        //查询权限
        $permission = $manager->getPermissions();
        $permissions = [];
        foreach ($permission as $per){
            $permissions[$per->name] = $per->description;
        }

        //判断是否有提交
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){

                    $role = $manager->createRole($model->name);
                    $role->description = $model->description;
                    $manager->add($role);
                    $arrs = $model->permission;
//                $role = $manager->getRole($model->name);
//                var_dump($arrs);exit;
//                var_dump($role);exit;
                    foreach ($arrs as $arr){
                        $permission = $manager->getPermission($arr);
//                    var_dump($arr);
                        $manager->addChild($role,$permission);
                    }
//                exit;
                    //提示信息
                    \Yii::$app->session->setFlash('success','添加角色成功');
                    //跳转
                    return $this->redirect(['rbac/role-index']);


            }
        }


        //加载视图
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);
    }
    //角色的列表功能
    public function actionRoleIndex(){
        $manager = \Yii::$app->authManager;
        $roles = $manager->getRoles();
//        var_dump($roles);exit;
        return $this->render('role-index',['roles'=>$roles]);
    }
    //角色的删除
    public function actionRoleDelete(){
//实例化request组件
        $request = \Yii::$app->request;
        $name = $request->post('name');
        //实例化manager组件
        $manager = \Yii::$app->authManager;
        $role = $manager->getRole($name);
        $res = $manager->remove($role);
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
    //角色的修改
    public function actionRoleEdit($name){
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化manager组件
        $manager = \Yii::$app->authManager;
        //查询修改的角色
        $role = $manager->getRole($name);
        if($role==null){
            throw new HttpException(404,'权限不存在');
        }
        //实例化表单模型
        $model = new RoleForm();
        $model->scenario = PermissionForm::SCENARIO_EDIT;
        $model->name = $role->name;
        $model->description = $role->description;
        $RolePermissions =$manager->getChildren($name);
        foreach ($RolePermissions as $permission){
            $model->permission[]=$permission->name;
        }
        //查询权限
        $permission = $manager->getPermissions();
        $permissions = [];
        foreach ($permission as $per){
            $permissions[$per->name] = $per->description;
        }

        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //删除之前的权限

                $role->name = $model->name;
                $role->description = $model->description;
                $manager->update($name,$role);
                $manager->removeChildren($role);


                $arrs = $model->permission;
                if (is_array($arrs)){
                    foreach ($arrs as $arr){
                        $permi = $manager->getPermission($arr);

                        $manager->addChild($role,$permi);
                    }
                }


                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转页面
                return $this->redirect(['rbac/role-index']);
            }
        }

        //加载视图
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);

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