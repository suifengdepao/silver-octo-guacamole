<?php
namespace backend\models;

use yii\base\Model;

class PowerForm extends Model{
    public $name;//权限名称
    public $description;//权限介绍

    //规则
    public function rules(){
        return [
            [['name','description'],'required'],
        ];
    }
    //权限注释
    public function attributeLabels(){
        return [
            'name'=>'权限名称',
            'description'=>'权限介绍',
        ];
    }
    //添加权限
    public function addAuthority(){
        $authmanager=\Yii::$app->authManager;
        //创建权限前判断是否已有该数据
        if($authmanager->getPermission($this->name)){
            //有的情况就提示
            $this->addError('name','权限名已存在');
        }else{
            //没有就添加数据
            $power=$authmanager->createPermission($this->name);
            $power->description=$this->description;
            return $authmanager->add($power);
        }
        return false;
    }
    //修改权限
    public function updateAuthority($name){
        $authmanager=\Yii::$app->authManager;
        //查询出数据
        $authority=$authmanager->getPermission($name);
        //判断权限名是否修改，修改后的权限名是否存在
        if($name!=$this->name && $authmanager->getPermission($this->name)){
            //存在，添加错误
            $this->addError('name','权限名已存在');
        }else{
            //不存在，修改数据
            $authority->name=$this->name;
            $authority->description=$this->description;
            return $authmanager->update($name,$authority);
        }
        return false;
    }
}