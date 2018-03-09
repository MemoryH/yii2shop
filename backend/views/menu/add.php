<?php

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($menu,'name')->textInput();

echo $form->field($menu,'parent_id')->dropDownList(\backend\models\Menu::getParent());

echo $form->field($menu,'address')->dropDownList($address);

echo $form->field($menu,'sort')->textInput();
echo "<button type='submit' class='btn btn-primary'>".($menu->getIsNewRecord()?'添加':'更新')."</button>";

\yii\bootstrap\ActiveForm::end();