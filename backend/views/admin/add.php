<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($admin,'username')->textInput();
if (empty($_GET['id'])){
    echo $form->field($admin,'password_hash')->textInput([
        'type'=>'password'
    ]);
    echo $form->field($admin,'confirm')->textInput([
        'type'=>'password'
    ]);
}else{
    echo $form->field($admin,'status',['inline'=>1])->radioList(['0'=>'启用','1'=>'禁用']);
}
echo $form->field($admin,'email')->textInput([
    'type'=>'email'
]);
echo $form->field($admin,'role',['inline'=>1])->checkboxList($roleArr);





echo "<button type='submit' class='btn btn-primary'>".($admin->getIsNewRecord()?'添加':'更新')."</button>";

\yii\bootstrap\ActiveForm::end();