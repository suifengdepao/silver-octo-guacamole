<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_155433_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
//            name	varchar(50)	名称
//intro	text	简介
//sort	int(11)	排序
//status	int(2)	状态(-1删除 0隐藏 1正常)
//is_help	int(1)	类型
            'name'=>$this->string(50),
            'intro'=>$this->text(),
            'sort'=>$this->integer(11),
            'status'=>$this->integer(2),
            'is_help'=>$this->integer(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
