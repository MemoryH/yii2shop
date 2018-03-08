<?php
$form = \yii\bootstrap\ActiveForm::begin();


echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->textInput([
    'type'=>'password'
]);
echo $form->field($model,'remember')->checkboxList(['1'=>'记住密码']);

//验证码
echo $form->field($model,'verifyCode')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'login/captcha',
    'template'=>'<div class="row"><div class="col-xs-2">{input}</div><div class="col-xs-1">{image}</div></div> '
]);

echo '<button type="submit" class="btn btn-primary">登录</button>';
\yii\bootstrap\ActiveForm::end();