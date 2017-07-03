<?php
echo \yii\bootstrap\Html::beginForm(\yii\helpers\Url::to(['goods/index']),'get');
echo \yii\bootstrap\Html::textInput('keyword');
echo \yii\bootstrap\Html::textInput('sn');
echo \yii\bootstrap\Html::textInput('price');
echo \yii\bootstrap\Html::submitButton('搜索');
echo \yii\bootstrap\Html::endForm();

?>
<?=\yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>货号</th>
        <th>logo</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($mould as $good):?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?=\yii\bootstrap\Html::img($good->logo,['width'=>60])?></td>
            <td><?=$good->goods_category_id?></td>
            <td><?=$good->brand_id?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=$good->is_on_sale?></td>
            <td><?=$good->status?></td>
            <td><?=$good->sort?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$good->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('详情',['goods/content','id'=>$good->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('图片',['goods/photo','id'=>$good->id],['class'=>'btn btn-warning btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination'=>$page
])?>