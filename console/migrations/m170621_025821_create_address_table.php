<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_025821_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment('收货人'),
            'province'=>$this->string(20)->notNull()->comment('省'),
            'city'=>$this->string(10)->notNull()->comment('市'),
            'area'=>$this->string(50)->notNull()->comment('区'),
            'detailed'=>$this->string()->notNull()->comment('详细地址'),
            'tel'=>$this->integer(11)->notNull()->comment('电话'),
            'default'=>$this->integer(1)->comment('是否是默认地址'),
            'member_id'=>$this->integer()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
