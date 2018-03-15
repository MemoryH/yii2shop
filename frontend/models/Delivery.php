<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property string $delivery_name 配送方式名称
 * @property double $delivery_price 配送方式价格
 * @property string $delivery_intro 配送方式详情
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_price'], 'number'],
            [['delivery_name'], 'string', 'max' => 50],
            [['delivery_intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'delivery_intro' => '配送方式详情',
        ];
    }
}
