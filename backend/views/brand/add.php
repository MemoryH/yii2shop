<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
//================================================
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
$logo_url = \yii\helpers\Url::to(['brand/logo']);
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
    $('#brand-logo').val(imgFile)
    //图片回显
    $('#img_logo').attr('src',imgFile);
    $( '#'+file.id ).addClass('upload-state-done');
});
JS

);

echo "<img id='img_logo'>";




//=======================================================
echo $form->field($model,'sort')->textInput();
echo '<button type="submit" class="btn btn-primary">'.($model->getIsNewRecord()?'添加':'更新').'</button>';
\yii\bootstrap\ActiveForm::end();