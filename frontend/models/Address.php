<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $tel 联系电话
 * @property string $name 收货人姓名
 * @property string $province 省
 * @property string $city 市
 * @property string $county 县
 * @property int $member_id 收货人ID
 * @property int $status 0:正常 1:默认
 * @property string $address 详细地址
 * @property int $create_time 创建时间
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tel', 'name', 'province', 'city', 'county'], 'required'],
            [['member_id', 'status', 'create_time'], 'integer'],
            [['tel','name', 'province', 'city', 'county'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tel' => '联系电话',
            'name' => '收货人姓名',
            'province' => '省',
            'city' => '市',
            'county' => '县',
            'member_id' => '收货人ID',
            'status' => '0:正常 1:默认',
            'address' => '详细地址',
            'create_time' => '创建时间',
        ];
    }
}
