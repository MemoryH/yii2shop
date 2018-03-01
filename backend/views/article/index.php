
<a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>分类</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->articleCategory->name?></td>
            <td><?=$article->sort?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
                <?=\yii\helpers\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-info'])?>
                <?=\yii\helpers\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'btn btn-info'])?>
                <?=\yii\helpers\Html::a('查看',['article/show','id'=>$article->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>