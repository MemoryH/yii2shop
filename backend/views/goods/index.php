<a href="<?= \yii\helpers\Url::to(['goods/add']) ?>" class="btn btn-primary">添加</a>
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
        <tr>
            <td><?= $good->id ?></td>
            <td><?= $good->sn ?></td>
            <td><?= $good->name ?></td>
            <td><?= $good->shop_price ?></td>
            <td><?= $good->stock ?></td>
            <td><img src="<?= $good->logo ?>" class="img-circle" width="30px"></td>
            <td>

                <a class="btn btn-default" href="photo.html?id=<?=$good->id?>"><span class="glyphicon glyphicon-picture"></span>相册</a>
                <a class="btn btn-warning" href="edit.html?id=<?=$good->id?>"><span class="glyphicon glyphicon-edit"></span>编辑</a>
                <a class="btn btn-danger" href="delete.html?id=<?=$good->id?>"><span class="glyphicon glyphicon-trash"></span>删除</a>

                <a class="btn btn-success" href=""><span class="glyphicon glyphicon-film"></span>预览</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="7"><a class="btn btn-danger" href="recycle.html"><span class="glyphicon glyphicon-trash"></span>回收站</a></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $page
]);