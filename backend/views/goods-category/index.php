
<a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr delete-id=<?=$model->id?>>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td>
                <a class="btn btn-warning" href="edit.html?id=<?=$model->id?>"><span class="glyphicon glyphicon-edit"></span>编辑</a>
                <a class="btn btn-danger delete" href="#"><span class="glyphicon glyphicon-trash"></span>删除</a>
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
