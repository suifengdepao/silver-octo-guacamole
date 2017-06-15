<?php
namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    static public $status=[0=>'隐藏',1=>'正常',-1=>'删除'];
    public function rules(){
        return [
            [['name','intro','status','article_category_id'],'required'],
            ['intro','string'],
            [['sort','status'],'integer']
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'文章名',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
            'article_category_id'=>'文章分类'
        ];
    }
    public function behaviors(){
        return [
            'time'=>[
                'class'=>TimestampBehavior::className(),
                'attributes'=>[
                    self::EVENT_BEFORE_INSERT=>['create_time'],
                ]
            ]
        ];
    }
}