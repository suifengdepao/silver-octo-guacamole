<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($mould,'name');
echo $form->field($mould,'goods_category_id')->dropDownList($cate);
echo $form->field($mould,'brand_id')->dropDownList($brand);
echo $form->field($mould,'market_price');
echo $form->field($mould,'shop_price');
echo $form->field($mould,'stock');
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
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);

if($mould->logo){
    echo \yii\bootstrap\Html::img($mould->logo,['width'=>60]);
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img_logo','width'=>60]);
}
echo $form->field($intro,'content')->widget('kucha\ueditor\UEditor',[]);


echo $form->field($mould,'is_on_sale')->radioList(\backend\models\Goods::$sale);
echo $form->field($mould,'status')->radioList(\backend\models\Goods::$status);
echo $form->field($mould,'sort');
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
?>

