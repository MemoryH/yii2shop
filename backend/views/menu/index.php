
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>路由</th>
        <th>操作</th>
    </tr>
    <?php foreach ($menus as $menu):?>
        <tr delete-id = <?=$menu->id?>>
            <td><?=$menu->id?></td>
            <td><?=$menu->name?></td>
            <td><?=$menu->address?></td>
            <td>
                <a class="btn btn-warning" href="edit.html?id=<?=$menu->id?>"><span class="glyphicon glyphicon-edit"></span>编辑</a>
                <a class="btn btn-danger delete" href="#"><span class="glyphicon glyphicon-trash"></span>删除</a>


            </td>
        </tr>
        <?php $menu_child = \backend\models\Menu::find()->where(['parent_id'=>$menu->id])->all()?>
        <?php if (is_array($menu_child)):foreach ($menu_child as $child):?>
            <tr delete-id = <?=$child->id?>>
                <td><?=$child->id?></td>
                <td>------<?=$child->name?></td>
                <td><?=$child->address?></td>
                <td>
            <?php if (Yii::$app->user->can('menu/edit')):?>
                    <a class="btn btn-warning" href="edit.html?id=<?=$menu->id?>"><span class="glyphicon glyphicon-edit"></span>编辑</a>
                <?php endif;?>
            <?php if (Yii::$app->user->can('menu/delete')):?>
                    <a class="btn btn-danger delete" href="#"><span class="glyphicon glyphicon-trash"></span>删除</a>
                <?php endif;?>


                </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
    <?php endforeach;?>
</table>



<?php
/**
 * @var $this \yii\web\View
 */

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
