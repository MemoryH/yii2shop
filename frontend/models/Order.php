<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property string $name 收货人
 * @property string $province 省
 * @property string $city 市
 * @property string $area 县
 * @property string $address 详细地址
 * @property string $tel 电话号码
 * @property int $delivery_id 配送id
 * @property string $delivery_name 配送方式名称
 * @property double $delivery_price 配送方式价格
 * @property int $payment_id 支付方式id
 * @property string $payment_name 支付方式名称
 * @property string $total 订单金额
 * @property int $status 0已取消1待付款2待发货3待收货4完成
 * @property string $trade_no 第三方支付交易号
 * @property int $create_time 订单创建时间
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name', 'delivery_name', 'payment_name'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'trade_no'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '0已取消1待付款2待发货3待收货4完成',
            'trade_no' => '第三方支付交易号',
            'create_time' => '订单创建时间',
        ];
    }
}
