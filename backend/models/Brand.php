<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $is_deleted
 */
class Brand extends \yii\db\ActiveRecord
{
    public $imgFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort'], 'required'],
            [['intro'], 'string'],
            [['sort', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
            ['imgFile','file','extensions' => ['png', 'jpg', 'gif']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '品牌名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'is_deleted' => '状态 0:正常 1:删除',
        ];
    }
}
