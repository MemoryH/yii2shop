
<a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><img src="<?=$brand->logo?>" class="img-circle" width="30px"></td>
            <td><?=$brand->sort?></td>
            <td>
                <?=\yii\helpers\Html::a('修改',['edit','id'=>$brand->id],['class'=>'btn btn-info'])?>
                <?=\yii\helpers\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>