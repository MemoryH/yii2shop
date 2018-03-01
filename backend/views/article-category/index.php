
<a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td>
                <?=\yii\helpers\Html::a('修改',['article-category/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                <?=\yii\helpers\Html::a('删除',['article-category/delete','id'=>$model->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page
]);