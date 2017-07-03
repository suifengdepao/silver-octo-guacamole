<?=\yii\bootstrap\Html::a('添加',['rbac/add-role'],['class'=>'btn btn-info']);?>
<table class="table">
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($mould as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$role->name],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$role->name],['class'=>'btn btn-danger btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>