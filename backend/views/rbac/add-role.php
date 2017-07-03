<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($role,'name');
echo $form->field($role,'description');
echo $form->field($role,'powers')->checkboxList(\backend\models\RoleForm::powers());
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();