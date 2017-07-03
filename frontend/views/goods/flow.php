<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=\yii\helpers\Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>



<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php $num=0;?>
        <?php foreach ($carts as $cart):?>
        <tr id="<?=$cart['id']?>">
            <td class="col1"><a href=""><?=\yii\helpers\Html::img($cart['logo'])?></a>  <strong><?=\yii\helpers\Html::a($cart['name'],'')?></strong></td>
            <td class="col3">￥<span><?=$cart['shop_price']?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?=$cart['amount']?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <?php $num+=$cart['shop_price']*$cart['amount'];?>
            <td class="col5">￥<span><?=$cart['shop_price']*$cart['amount']?></span></td>
            <td class="col6"><a href="javascript:;" class="del_num">删除</a></td>
        </tr>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total"><?=$num?></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <?=\yii\helpers\Html::a('结算',['order/fff'],['class'=>'checkout']);?>
    </div>
</div>
<!-- 主体部分 end -->

<?php
$url=\yii\helpers\Url::to(['goods/update']);
$token=\Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    //监听+-事件
    $('.reduce_num,.add_num').click(function() {
      var goods_id=$(this).closest('tr').attr('id');
      var amount=$(this).parent().find('.amount').val();
      console.debug(amount);
      $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"});
    });
    //监听删除事件
    $('.del_num').click(function() {
      if(confirm('是否删除此商品')){
          var goods_id=$(this).closest('tr').attr('id');
          $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
          $(this).closest('tr').remove();
      }
    });
JS

));