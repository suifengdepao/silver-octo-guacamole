<?php

use yii\db\Migration;

/**
 * Handles the creation of table `acticle`.
 */
class m170608_114747_create_acticle_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('acticle', [
            'id' => $this->primaryKey(),
//            name	varchar(50)	名称
//intro	text	简介
//article_category_id	int()	文章分类id
//sort	int(11)	排序
//status	int(2)	状态(-1删除 0隐藏 1正常)
//create_time	int(11)	创建时间
            'name'=>$this->string(50)->comment('文章名称'),
            'intro'=>$this->text()->comment('简介'),
            'article_category_id'=>$this->integer()->comment('文章分类'),
            'sort'=>$this->integer()->comment('排序'),
            'status'=>$this->integer()->comment('状态'),
            'create_time'=>$this->integer()->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('acticle');
    }
}
