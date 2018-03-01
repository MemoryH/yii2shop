
<a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td>
                <?=\yii\helpers\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                <?=\yii\helpers\Html::a('删除',['goods-category/delete','id'=>$model->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page
]);