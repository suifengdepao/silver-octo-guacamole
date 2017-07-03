<?=\yii\bootstrap\Html::a('添加',['rbac/add-authority'],['class'=>'btn btn-info']);?>
<table class="table">
    <tr>
        <th>权限名</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($authority as $row):?>
        <tr>
            <td><?=$row->name?></td>
            <td><?=$row->description?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-authority','name'=>$row->name],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('删除',['rbac/del-authority','name'=>$row->name],['class'=>'btn btn-danger btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>