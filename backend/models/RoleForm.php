<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 15:40
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permission;

    const SCENARIO_ADD =  'add';
    const SCENARIO_EDIT =  'edit';

    public function rules()
    {
        return [
            [['name','description'],'required'],
            //['name','unique']//不能使用该方法
            ['name','validateName','on'=>self::SCENARIO_ADD],
            ['name','ChangName','on'=>self::SCENARIO_EDIT],
            [['permission'],'safe'],
        ];
    }

    public function validateName(){
        $authManager = \Yii::$app->authManager;
        //获取权限
        if($authManager->getRole($this->name)){
            //权限已存在
            $this->addError('name','用户已存在');
        }
    }

    public function ChangName(){
        //如果修改了name,需要验证name是否存在
        if(\Yii::$app->request->get('name') != $this->name){
            $this->validateName();
        }
        //旧name

        //$this->name;//修改后的name
        //如果没有修改name,则不验证name
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'描述',
            'permission'=>'权限',
        ];
    }
}