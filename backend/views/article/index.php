
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
        <tr delete-id=<?=$article->id?>>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->articleCategory->name?></td>
            <td><?=$article->sort?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
                <a class="btn btn-warning" href="edit.html?id=<?=$article->id?>"><span class="glyphicon glyphicon-edit"></span>编辑</a>
                <a class="btn btn-danger delete" href="#"><span class="glyphicon glyphicon-trash"></span>删除</a>
                <?=\yii\helpers\Html::a('查看',['article/show','id'=>$article->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page
]);

$this->registerJs(
    <<<JS
    $('.delete').click(function() {
       
    if(confirm("真的要删除吗?")){
    var tr = $(this).closest('tr');
      var id = $(this).closest('tr').attr('delete-id');
    var data = {
      'id':id
    };
      $.post('delete.html',data,function(arr) {
        if (arr.status ==0){
            tr.remove();
        }
    },'json');
  }
  else{
  
  }
  
    })
JS

);