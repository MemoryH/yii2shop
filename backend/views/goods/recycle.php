
<form id="w0" class="form-inline" action="" method="get" role="form">
    <div class="form-group field-goodssearchform-name">
        <input type="text" id="goodssearchform-name" class="form-control" name="SearchForm[name]"
               placeholder="商品名" value="<?= $_GET['SearchForm']['name']??'' ?>">
    </div>
    <div class="form-group field-goodssearchform-sn">
        <input type="text" id="goodssearchform-sn" class="form-control" name="SearchForm[sn]" placeholder="货号"
               value="<?= $_GET['SearchForm']['sn']??'' ?>">
    </div>
    <div class="form-group field-goodssearchform-minprice">
        <input type="text" id="goodssearchform-minprice" class="form-control" name="SearchForm[minPrice]"
               placeholder="￥" value="<?= $_GET['SearchForm']['minPrice']??'' ?>">
    </div>
    <div class="form-group field-goodssearchform-maxprice">
        <label class="sr-only" for="goodssearchform-maxprice">-</label>
        <input type="text" id="goodssearchform-maxprice" class="form-control" name="SearchForm[maxPrice]"
               placeholder="￥" value="<?= $_GET['SearchForm']['maxPrice']??'' ?>">
    </div>
    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>搜索</button>
</form>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>货号</th>
        <th>名称</th>
        <th>价格</th>
        <th>库存</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good): ?>
        <tr delete-id=<?= $good->id ?>>
            <td><?= $good->id ?></td>
            <td><?= $good->sn ?></td>
            <td><?= $good->name ?></td>
            <td><?= $good->shop_price ?></td>
            <td><?= $good->stock ?></td>
            <td><img src="<?= $good->logo ?>" class="img-circle" width="30px"></td>
            <td>

                <a class="btn btn-success delete" href="#"><span class="glyphicon glyphicon-film"></span>恢复</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="7"><a class="btn btn-danger" href="index.html"><span class="glyphicon glyphicon-trash"></span>返回列表</a></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $page
]);

$this->registerJs(
    <<<JS
    $('.delete').click(function() {
       
    if(confirm("真的要恢复吗?")){
    var tr = $(this).closest('tr');
      var id = $(this).closest('tr').attr('delete-id');
    var data = {
      'id':id
    };
      $.post('recovery.html',data,function(arr) {
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