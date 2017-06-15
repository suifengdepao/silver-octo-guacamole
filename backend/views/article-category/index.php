<?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    <?php foreach($category as $in):?>
        <tr>
            <td><?=$in->id?></td>
            <td><?=$in->name?></td>
            <td><?=$in->intro?></td>
            <td><?=$in->sort?></td>
            <td><?=\backend\models\Brand::$status[$in->status]?></td>
            <td><?=$in->is_help?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$in->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$in->id],['class'=>'btn btn-warning btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>