<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [[ 'sort'], 'integer'],
            ['parent_id','safe'],
            [['name'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'url' => '地址',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }
    //一对多获取子菜单
    public function getChilds(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
