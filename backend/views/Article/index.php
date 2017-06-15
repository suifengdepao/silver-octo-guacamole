<?=\yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>简介</th>
        <th>所属分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($article as $art):?>
        <tr>
            <td><?=$art->id?></td>
            <td><?=$art->name?></td>
            <td><?=$art->intro?></td>
            <td><?=$art->article_category_id?></td>
            <td><?=$art->sort?></td>
            <td><?=\backend\models\Brand::$status[$art->status]?></td>
            <td><?=$art->status?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$art->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$art->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('详情',['article/deta','id'=>$art->id],['class'=>'btn btn-warning btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>