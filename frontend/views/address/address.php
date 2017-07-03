<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <dl>
            <?php foreach ($rows as $row):?>
                    <dt><?=$row->id?> <?=$row->name?> <?=$row->province?> <?=$row->city?> <?=$row->area?> <?=$row->detailed?> <?=$row->tel?></dt>
                    <dd>
                        <?=\yii\helpers\Html::a('修改',['loca-edit','id'=>$row->id])?>
                        <?=\yii\helpers\Html::a('删除',['loca-del','id'=>$row->id])?>
                        <?=\yii\helpers\Html::a('设为默认地址',['fit','id'=>$row->id])?>
                    </dd>
            <?php endforeach;?>
            </dl>

        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php
            $form=\yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'ul',
                    ],
                    'errorOptions'=>[
                        'tag'=>'p',
                    ]
                ]]
            );
            echo '<ul>';
            echo $form->field($address,'name')->textInput(['class'=>'txt']);
            echo '<li><label for="">所在地区：</label>';
            echo $form->field($address,'province',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList(['prompt' => '请选择省']);
            echo $form->field($address,'city',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList(['prompt' => '请选择城市']);
            echo $form->field($address,'area',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList(['prompt' => '请选择区']);

            echo $form->field($address,'detailed')->textInput(['class'=>'txt']);
            echo $form->field($address,'tel')->textInput(['class'=>'txt']);
            echo $form->field($address,'default')->checkbox(['class'=>'check']);
            echo '<br/><br/><br/>';
            echo \yii\helpers\Html::submitButton('保存');
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>

        </div>

    </div>
    <!-- 右侧内容区域 end -->

</div>
<!-- 页面主体 end-->
        <?php
        $this->registerJsFile('@web/js/address.js');
        $this->registerJs(new \yii\web\JsExpression(
                <<<JS
                //获取所有省得数据到页面
                $(address).each(function() {
                  var option='<option value="'+this.name+'">'+this.name+'</option>';
                  $('#address-province').append(option);
                });
              //当省得下拉框变化时，获取选中省的对应市
              $('#address-province').change(function() {
                var option=$(this).val();
                //获取省对应的市
                $(address).each(function() {
                  if(this.name==option){
                      var city='<option value="">请选择城市</option>';
                    $(this.city).each(function() {
                      city +='<option value="'+this.name+'">'+this.name+'</option>';
                    });
                    $('#address-city').html(city);
                  }
                });
                //清除区的数据
                $('#address-area').html('<option value="">请选择区</option>');
              });
               //选择市后，根据市查询出区的数据
                $('#address-city').change(function() {
                  var option=$(this).val();
                  //获取市对应的区
                  $(address).each(function() {
                      if(this.name==$('#address-province').val()){
                          $(this.city).each(function() {
                            if(this.name==option){
                            var area='<option value="">请选择区</option>';
                            $(this.area).each(function(i,v) {
                              area +='<option value="'+v+'">'+v+'</option>';
                            });
                            $('#address-area').html(area);
                            }
                          })
                      }
                    
                  });
                });
JS

        ));
        $js='';
        if($address->province){
            $js .= '$("#address-province").val("'.$address->province.'");';
        }
        if($address->city){
            $js .= '$("#address-province").change();$("#address-city").val("'.$address->city.'");';
        }
        if($address->area){
            $js .= '$("#address-city").change();$("#address-area").val("'.$address->area.'");';
        }
        $this->registerJs($js);


