<?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($mould as $in):?>
    <tr>
        <td><?=$in->id?></td>
        <td><?=$in->name?></td>
        <td><?=$in->intro?></td>
        <td><?php if($in->logo){echo "<img src='$in->logo'width='60'/>";} ?></td>
        <td><?=$in->sort?></td>
        <td><?=\backend\models\Brand::$status[$in->status]?></td>
        <td><?php if(\Yii::$app->user->can('brand/edit')){
            echo \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$in->id],['class'=>'btn btn-warning btn-xs']);}
            if(\Yii::$app->user->can('brand/del')){
        echo \yii\bootstrap\Html::a('删除',['brand/del','id'=>$in->id],['class'=>'btn btn-warning btn-xs']);}
        ?></td>
    </tr>
    <?php endforeach;?>
</table>