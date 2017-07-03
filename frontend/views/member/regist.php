<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <?php
            /*$form=\yii\widgets\ActiveForm::begin(
                    ['fieldConfig'=>[
                            'options'=>[
                                    'tag'=>'li',
                            ],
                            'errorOptions'=>[
                                    'tag'=>'p',
                            ]
                    ]]
            );
            echo '<ul>';
            echo $form->field($mould,'username')->textInput(['class'=>'txt']);
            echo $form->field($mould,'password_hash')->passwordInput(['class'=>'txt']);
            echo $form->field($mould,'tel')->textInput(['class'=>'txt']);
//            $button= \yii\helpers\Html::button('发送验证码',['id'=>'send_sms']);
//            echo $form->field($mould,'sms',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}$button\n{hint}\n{error}"]);
            echo $form->field($mould,'email')->textInput(['class'=>'txt']);
            echo $form->field($mould,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className());
            echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" />
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();*/
            ?>

<!--            <form action="" method="post">-->
                <ul>
                    <li>
                        <label for="">用户名：</label>
                        <input type="text" class="txt" name="username" id="username"/>
                        <p>3-20位字符，可由中文、字母、数字和下划线组成</p>
                    </li>
                    <li>
                        <label for="">密码：</label>
                        <input type="password" class="txt" name="password" id="password"/>
                        <p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
                    </li>
                    <li>
                        <label for="">邮箱：</label>
                        <input type="text" class="txt" name="email" id="email"/>
                        <p>邮箱必须合法</p>
                    </li>
                    <li>
                        <label for="">手机号码：</label>
                        <input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>
                    </li>

                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" id="ti"/>
                    </li>
                </ul>
<!--            </form>-->
        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->

<?php
/**
 * @var $this \yii\web\View
 */
/*$url=\yii\helpers\Url::to(['member/send-sms']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $('#send_sms').click(function() {
      //手机号
      var tel=$('#member-tel').val();
      //提交数据
      $.post('$url',{tel:tel},function(date) {
        if(date=='success'){
            alert('短信发送成功');
        }else{
            alert(date);
        }
      });
    });
JS

));*/
$url='http://www.yii2log.com/api/user.html';
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
    $('#ti').click(function() {
      var username=$('#username').val();
      var password=$('#password').val();
      var tel=$('#tel').val();
      var email=$('#email').val();
      var zhu={
          username:username,
          password:password,
          tel:tel,
          email:email
      };
      $.post('$url',zhu,function(json){
          if(json.status==1){
              self.location='http://www.yii2log.com/member/login.html';
          }else{
              alert(json.msg);
          }
      });
    })
JS

));