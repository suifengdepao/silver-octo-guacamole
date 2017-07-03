<?=\yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>菜单名</th>
        <th>路径</th>
        <th>上级菜单</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($menu as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->url?></td>
            <td><?=$row->parent_id?></td>
            <td><?=$row->sort?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$row->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$row->id],['class'=>'btn btn-danger btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>