

<a href="<?=\yii\helpers\Url::to(['rbac/add-role'])?>" class="btn btn-primary">添加</a>

<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $role):?>
        <tr delete-name=<?=$role->name?>>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <a class="btn btn-warning" href="role-edit.html?name=<?=$role->name?>"><span class="glyphicon glyphicon-edit"></span>编辑</a>
                <a class="btn btn-danger delete" href="#"><span class="glyphicon glyphicon-trash"></span>删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
/**
 * @var $this \yii\web\View
 */

//引入datatable插件的css
$this->registerCssFile('@web/DataTables/media/css/jquery.dataTables.css');
//引入datatables插件的js
$this->registerJsFile('@web/DataTables/media/js/jquery.dataTables.js',[
        'depends'=>\yii\web\JqueryAsset::class,
]);

$this->registerJs(
    <<<JS
    $('.delete').click(function() {
       
    if(confirm("真的要删除吗?")){
    var tr = $(this).closest('tr');
      var name = $(this).closest('tr').attr('delete-name');
    var data = {
      'name':name
    };
      $.post('role-delete.html',data,function(arr) {
        if (arr.status ==0){
            tr.remove();
        }
    },'json');
  }
  else{
  
  }
  
    });


    $(document).ready( function () {
    $('#table_id_example').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
    });
} );
    
    
JS

);
