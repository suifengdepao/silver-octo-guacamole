<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name')->textInput();
echo $form->field($article,'intro')->textInput();
echo $form->field($detail,'content')->textarea();
echo $form->field($article,'article_category_id')->dropDownList($cate);
echo $form->field($article,'sort')->textInput();
echo $form->field($article,'status')->radioList([0=>'隐藏',1=>'正常']);
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();