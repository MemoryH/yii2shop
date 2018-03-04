<?php

namespace backend\models;

use app\models\Brand;
use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property string $sn 商品货号
 * @property string $logo LOGO图片
 * @property int $goods_category_id 商品分类id
 * @property int $brand_id 品牌分类id
 * @property string $market_price 市场价格
 * @property string $shop_price 商场价格
 * @property int $stock 库存
 * @property int $is_on_sale 是否在售 1:在售 0:下架
 * @property int $status 状态 1:正常 0:回收站
 * @property int $sort 排序
 * @property int $create_time 添加时间
 * @property int $view_times 浏览次数
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'goods_category_id', 'brand_id'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '商品货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类id',
            'market_price' => '市场价格',
            'shop_price' => '商场价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售 1:在售 0:下架',
            'status' => '状态 1:正常 0:回收站',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }

    //获取商品品牌分类
    public static function getBrand(){
        //实例化brand模型
        $brands = Brand::find()->all();
        //定义一个数组接收品牌id与名称的键值对
        $val = [];
        //循环键值对
        foreach ($brands as $brand){
            $val[$brand->id] = $brand->name;
        }
        return $val;
    }
}
