
<a href="<?=\yii\helpers\Url::to(['admin/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin):?>
        <tr delete-id = <?=$admin->id?>>
            <td><?=$admin->id?></td>
            <td><?=$admin->username?></td>
            <td><?=$admin->email?></td>
            <td><?=$admin->status?'禁用':'启用'?></td>
            <td><?=date('Y-m-d H:i:s',$admin->create_time)?></td>
            <td><?=date('Y-m-d H:i:s',$admin->update_time)?></td>
            <td><?=date('Y-m-d H:i:s',$admin->last_login_time)?></td>
            <td><?=$admin->last_login_ip?></td>
            <td>
                <a class="btn btn-warning" href="edit.html?id=<?=$admin->id?>"><span class="glyphicon glyphicon-edit"></span>编辑</a>
                <a class="btn btn-danger delete" href="#"><span class="glyphicon glyphicon-trash"></span>删除</a>


            </td>
        </tr>
    <?php endforeach;?>
</table>



<?php
/**
 * @var $this \yii\web\View
 */
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
