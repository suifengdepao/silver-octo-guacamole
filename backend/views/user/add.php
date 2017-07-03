<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($user,'username');
echo $form->field($user,'password_hash')->passwordInput();
echo $form->field($user,'tr_password')->passwordInput();
echo $form->field($user,'email');
echo $form->field($user,'status')->radioList([0=>'在线',1=>'离线']);
echo $form->field($user,'role')->checkboxList(\backend\models\User::getroles());
echo $form->field($user,'code')->widget(\yii\captcha\Captcha::className(),['captchaAction'=>'user/captcha']);
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();