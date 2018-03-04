<?php
//==================文件上传==============================
$form = \yii\bootstrap\ActiveForm::begin();
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
    
    var imgFile = response.url;
    // console.log(imgFile);
    $('#goodsgallery-path').val(imgFile);
    var id = $id;
    var data = {
      'path':imgFile,
      'goods_id':id
    };
    $.post('psave.html',data,function(arr) {
        
    },'json');
    //图片回显
   location.reload();

});



JS

);
echo "<a href='index.html' class='btn btn-primary'>返回</a>";
foreach ($photos as $photo){
    echo "<img id='img_logo' src=$photo->path >";
    echo "<a href='photo-delete.html?id=$photo->id&&goods_id=$photo->goods_id' class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span>删除</a>";
    echo "<hr>";
}



//==================文件上传==============================

\yii\bootstrap\ActiveForm::end();