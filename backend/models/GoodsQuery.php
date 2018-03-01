<?php
/**
 * Created by PhpStorm.
 * User: 悦悦
 * Date: 2018/3/1 0001
 * Time: 11:40
 */
namespace backend\models;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class GoodsQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}