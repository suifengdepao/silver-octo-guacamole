<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($authority,'name');
echo $form->field($authority,'description');
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();