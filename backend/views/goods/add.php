<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name')->textInput();
echo $form->field($goods,'logo')->hiddenInput();

//==================文件上传==============================

//引入css
$this->registerCssFile('@web/webuploader/webuploader.css');

//引入js
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);

echo <<<HTML
    <!--dom结构部分-->
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
    </div>
HTML;

//文件接收地址
$logo_url = \yii\helpers\Url::to(['goods/logo']);
$this->registerJs(
    <<<JS
    // 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/webuploader/Uploader.swf',

    // 文件接收服务端。
    server: '{$logo_url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    }
});

// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file,response ) {
    
    var imgFile = response.url
    // console.log(imgFile);
    $('#goods-logo').val(imgFile)
    //图片回显
    $('#img_logo').attr('src',imgFile);
    $( '#'+file.id ).addClass('upload-state-done');
});

JS

);

echo "<img id='img_logo' src=$goods->logo >";
//==================文件上传==============================




echo $form->field($goods,'goods_category_id')->hiddenInput();
//==================商品分类=========================================================
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::class
]);

//写Js
$this->registerJs(
    <<<JS
  var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       data:{
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
	        },
	    callback: {
            onClick: function(event, treeId, treeNode) {
                $('#goods-goods_category_id').val(treeNode.id)
            }
	}  
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes ={$nodes} ;
   
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
      zTreeObj.expandAll(true);
      zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->id}", null));
   
JS

);

//写html
echo '<div>
   <ul id="treeDemo" class="ztree"></ul>
</div>';
//==================商品分类=================


echo $form->field($goods,'brand_id')->dropDownList(\backend\models\Goods::getBrand());
echo $form->field($goods,'market_price')->textInput();
echo $form->field($goods,'shop_price')->textInput();
echo $form->field($goods,'stock')->textInput();
echo $form->field($goods,'is_on_sale',['inline'=>1])->radioList([
    1=>'上架',
    2=>'下架',
]);
echo $form->field($goods,'sort')->textInput();
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
    ]
]);


echo "<button type='submit' class='btn btn-primary'>".($goods->getIsNewRecord()?'添加':'更新')."</button>";


\yii\bootstrap\ActiveForm::end();