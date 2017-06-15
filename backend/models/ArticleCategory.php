<?php
namespace backend\models;

use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord {
    static public $status=[0=>'隐藏',1=>'正常',-1=>'删除'];
    public function rules(){
        return [
            [['name','intro','status'],'required'],
            ['intro','string'],
            [['sort','status'],'integer']
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'分类名',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
            'is_help'=>'类型'
        ];
    }
}