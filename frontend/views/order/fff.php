<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=\yii\helpers\Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <?php $form=\yii\widgets\ActiveForm::begin();?>
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($rows as $row):?>
                    <p>
                        <input type="radio" value="<?=$row->id?>" name="address_id"/> <?=$row->name?>&emsp;<?=$row->tel?>&emsp;<?=$row->province?>&emsp;<?=$row->city?>&emsp;<?=$row->area?>
                    </p>
                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $modes=\frontend\models\Order::$mode;?>
                    <?php foreach ($modes as $k=>$mode):?>
                        <tr <?=$k==1?'class="cur"':'';?>>
                            <td><input type="radio" name="delivery_id"  value="<?=$k?>" <?=$k==1?'checked="checked"':'';?>  /><?=$mode['name']?></td>
                            <td>￥<?=$mode['price']?></td>
                            <td><?=$mode['info']?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php $pays=\frontend\models\Order::$pay;?>
                    <?php foreach ($pays as $k=>$pay):?>
                        <tr <?=$k==1?'class="cur"':'';?>>
                            <td class="col1"><input type="radio" name="payment_id" value="<?=$k?>"/><?=$pay['name']?></td>
                            <td class="col2"><?=$pay['info']?></td>
                        </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
       <!-- <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>-->
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $num=0;?>
                <?php foreach ($wares as $ware):?>
                    <tr>
                        <td class="col1"><a href=""><?=\yii\helpers\Html::img($ware['logo'])?></a><strong><?=\yii\helpers\Html::a($ware['name'],'')?></strong> </td>
                        <td class="col3"><a href="">￥<?=$ware['shop_price']?></a> </td>
                        <td class="col4"><?=$ware['amount']?></td>
                        <td class="col5"><span>￥<?=$ware['shop_price']*$ware['amount']?></span></td>
                    </tr>
                    <?php $num+=$ware['shop_price']*$ware['amount'];?>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span>4 件商品，总商品金额：</span>
                                <em>￥<?=$num?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥240.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em>￥10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<?=$num?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->
    </div>

    <div class="fillin_ft">
        <input name="_csrf-frontend" type="hidden" id="_csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>"><!--yii自带的防post数据攻击，这句可以跳过防护-->
        <span><?=\yii\helpers\Html::submitButton('提交订单')?></span>
        <p>应付总额：<strong>￥<?=$num?></strong></p>

    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</div>
<!-- 主体部分 end -->
