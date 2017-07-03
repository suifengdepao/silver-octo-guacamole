<?=\yii\bootstrap\Html::a('添加',['goods-category/add'])?>
<table class="table cate">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>上级分类名</th>
        <th>操作</th>
    </tr>
    <?php foreach ($gui as $fei):?>
        <tr date-lft="<?=$fei->lft?>" date-rgt="<?=$fei->rgt?>" date-tree="<?=$fei->tree?>">
            <td><?=$fei->id?></td>
            <td><?=str_repeat(' - ',$fei->depth).$fei->name?>
            <span class="toggle_cate glyphicon glyphicon-chevron-down" style="float: right"></span></td>
            <td><?=$fei->parent_id?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$fei->id])?><?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$fei->id])?></td>
        </tr>
    <?php endforeach;?>
</table>

<?php
    $js=<<<EOT
        $(".toggle_cate").click(function () {
            var show = $(this).hasClass("glyphicon-chevron-up");
            $(this).toggleClass("glyphicon-chevron-down");
            $(this).toggleClass("glyphicon-chevron-up");
            var tr=$(this).closest('tr');
            var tree=parseInt(tr.attr('date-tree'));
            var lft=parseInt(tr.attr('date-lft'));
            var rgt=parseInt(tr.attr('date-rgt'));
            $(".cate tr").each(function() {
               if(parseInt($(this).attr('date-tree'))==tree && parseInt($(this).attr('date-lft'))>lft && parseInt($(this).attr('date-rgt'))<rgt){
                   show?$(this).fadeIn():$(this).fadeOut();
               }
            });
        });
EOT;
    $this->registerJs($js);
