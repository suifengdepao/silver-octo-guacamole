<!-- 登录主体部分start -->
<div class="login w990 bc mt10">
    <div class="login_hd">
        <h2>用户登录</h2>
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
            echo $form->field($login,'username')->textInput(['class'=>'txt']);
            echo $form->field($login,'password_hash')->passwordInput(['class'=>'txt']);
            echo $form->field($login,'cookie')->checkbox();
            echo '<br/>';
            echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" />
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();*/
            ?>

            <ul>
                <li>
                    <label for="">用户名：</label>
                    <input type="text" class="txt" name="username" id="username"/>
                </li>
                <li>
                    <label for="">密码：</label>
                    <input type="password" class="txt" name="password" id="password"/>
                </li>
                <li>
                    <label for="">&nbsp;</label>
                    <input type="checkbox" class="chb"  /> 保存登录信息
                </li>
                <li>
                    <label for="">&nbsp;</label>
                    <input type="submit" value="" class="login_btn" id="ti"/>
                </li>
            </ul>
            <div class="coagent mt15">
                <dl>
                    <dt>使用合作网站登录商城：</dt>
                    <dd class="qq"><a href=""><span></span>QQ</a></dd>
                    <dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
                    <dd class="yi"><a href=""><span></span>网易</a></dd>
                    <dd class="renren"><a href=""><span></span>人人</a></dd>
                    <dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
                    <dd class=""><a href=""><span></span>百度</a></dd>
                    <dd class="douban"><a href=""><span></span>豆瓣</a></dd>
                </dl>
            </div>
        </div>

        <div class="guide fl">
            <h3>还不是商城用户</h3>
            <p>现在免费注册成为商城用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

            <a href="regist.html" class="reg_btn">免费注册 >></a>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<?php
$url='http://www.yii2log.com/api/login.html';
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $('#ti').click(function() {
      var username=$('#username').val();
      var password=$('#password').val();
      var zhu={
          username:username,
          password:password
      };
      $.post('$url',zhu,function(json){
          if(json.status==1){
              self.location='http://www.yii2log.com/member/index.html';
          }else{
              alert(json.msg);
          }
      });
    })
JS

));