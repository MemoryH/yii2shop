<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $auth_key 自动登录密匙
 * @property string $password_hash 密码
 * @property string $email 邮箱
 * @property string $tel 电话号码
 * @property int $last_login_time 最后登录时间
 * @property int $last_login_ip 最后登陆Ip
 * @property int $status 1:正常 0:删除
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class MemberApi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'email', 'tel'], 'required'],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key'], 'string', 'max' => 50],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '自动登录密匙',
            'password_hash' => '密码',
            'email' => '邮箱',
            'tel' => '电话号码',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登陆Ip',
            'status' => '1:正常 0:删除',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }
}
