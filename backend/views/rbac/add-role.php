<?php
$form = \yii\bootstrap\ActiveForm::begin();


echo $form->field($model,'name')->textInput([
]);
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permission',['inline'=>1])->checkboxList($permissions);
echo "<button type='submit' class='btn btn-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();