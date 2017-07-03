<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($menu,'name');
echo $form->field($menu,'url');
echo $form->field($menu,'parent_id')->dropDownList($cate);
echo $form->field($menu,'sort');
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
