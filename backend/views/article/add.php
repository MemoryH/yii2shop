<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();

echo $form->field($content,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
        ]
]);
//echo $form->field($content,'content')->textarea();
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\Article::getCategoryId());
echo $form->field($model,'sort')->textInput();
echo '<button type="submit" class="btn btn-primary">'.($model->getIsNewRecord()?'添加':'更新').'</button>';

\yii\bootstrap\ActiveForm::end();