<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($mould,'name')->textInput();
echo $form->field($mould,'intro')->textarea();
echo $form->field($mould,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //上传成功后将图片地址写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功的图片地址写入logo字段
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);

if($mould->logo){
    echo \yii\bootstrap\Html::img('@web'.$mould->logo,['width'=>60]);
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img_logo','width'=>60]);
}
echo $form->field($mould,'sort')->textInput();
echo $form->field($mould,'status')->radioList([0=>'隐藏',1=>'正常']);
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();