<?=\yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($user as $name):?>
        <tr>
            <td><?=$name->id?></td>
            <td><?=$name->username?></td>
            <td><?=$name->status?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$name->id],['class'=>'btn btn-warning btn-xs'])?><?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$name->id],['class'=>'btn btn-warning btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>