<?php
namespace backend\models;
use yii\base\Model;

class EditPasswordForm extends Model{
    public $old;
    public $password;
    public $confirm;
    public function rules()
    {
        return [
            [['old','password','confirm'],'required'],
            ['confirm', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'old'=>'旧密码',
            'password'=>'新密码',
            'confirm' => '确认密码',
        ];
    }
}