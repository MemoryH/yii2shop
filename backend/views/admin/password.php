<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($admin,'username')->textInput([
    'readonly'=>'readonly'
]);

echo $form->field($password,'old')->textInput([
        'type'=>'password'
]);
echo $form->field($password,'password')->textInput([
    'type'=>'password'
]);
echo $form->field($password,'confirm')->textInput([
    'type'=>'password'
]);
echo "<button type='submit' class='btn btn-primary'>确认修改</button>";

\yii\bootstrap\ActiveForm::end();