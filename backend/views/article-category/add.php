<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($category,'name')->textInput();
echo $form->field($category,'intro')->textarea();
echo $form->field($category,'sort')->textInput();
echo $form->field($category,'status')->radioList([0=>'隐藏',1=>'正常']);
echo $form->field($category,'is_help')->radioList([1=>'是否是帮助文档']);
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();