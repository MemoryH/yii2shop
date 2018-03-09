<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name 菜单名称
 * @property int $parent_id 上级菜单id
 * @property string $address 路由/地址
 * @property int $sort 排序
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'address'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['name', 'address'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '上级菜单id',
            'address' => '路由/地址',
            'sort' => '排序',
        ];
    }

    public static function getParent(){
        $menus = [];
        $menus[0] = '顶级分类';
        $menu_category = Menu::find()->where(['parent_id'=>0])->all();
        foreach ($menu_category as $menu){
            $menus[$menu->id] = $menu->name;
        }

        return $menus;
    }
    public static function getMenu(){
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        $parents = [];
        foreach ($menus as $menu){

            $childrens = Menu::find()->where(['parent_id'=>$menu->id])->all();
//            var_dump($childrens);exit;
            $child = [];
            if (is_array($childrens)){
                foreach ($childrens as $children){
                    if (Yii::$app->user->can($children->address)){
                        $child[] = ['label' => $children->name, 'url' => [$children->address]];
                    }

                }
            }
//            var_dump($child);exit;
            if ($child){
                $parents[]=['label' => $menu->name,'items'=>$child];
            }



        }
        return $parents;
    }
}
