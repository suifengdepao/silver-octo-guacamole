<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170618_034618_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull()->comment('名称'),
            'url'=>$this->string(255)->comment('地址'),
            'parent_id'=>$this->integer()->notNull()->comment('上级菜单'),
            'sort'=>$this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
