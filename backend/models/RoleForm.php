<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleForm extends Model{
    public $name;//角色名
    public $description;//角色描述
    public $powers=[];//权限

    public function rules(){
        return [
            [['name','description'],'required'],
            ['powers','safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'角色名',
            'description'=>'角色描述',
            'powers'=>'权限',
        ];
    }

    public static function powers(){
        //获取所有的权限信息
        $authority=\Yii::$app->authManager->getPermissions();
        return ArrayHelper::map($authority,'name','description');
    }

    //添加角色
    public function RoleAdd(){
        $authmanager=\Yii::$app->authManager;
        //判断用户名是否存在
        if($authmanager->getRole($this->name)){
            $this->addError('name','用户名已存在');
        }else{
            //不存在就添加角色
            $role=$authmanager->createRole($this->name);
            $role->description=$this->description;
            if($authmanager->add($role)){
                //循环添加角色和权限的关联
                foreach ($this->powers as $power){
                    //查找出选中的权限
                    $powername=$authmanager->getPermission($power);
                    //给角色添加选中的权限
                    if($powername) $authmanager->addChild($role,$powername);
                }
            }
            return true;
        }
        return false;
    }
    //修改角色
    public function updateRole($name){
        $authmanager=\Yii::$app->authManager;
        $mould=$authmanager->getRole($name);
        //赋值
        $mould->name=$this->name;
        $mould->description=$this->description;
        //判断角色名是否修改，修改后的名字是否存在
        if($name!=$this->name && $authmanager->getRole($this->name)){
            $this->addError('name','角色名已存在');
        }else{
            if($authmanager->update($name,$mould)){
                //删除角色的所有权限
                $authmanager->removeChildren($mould);
                //循环遍历关联角色和权限
                foreach ($this->powers as $power){
                    $power=$authmanager->getPermission($power);
                    if($power)$authmanager->addChild($mould,$power);
                }
                return true;
            }
        }
        return false;
    }
    //查询角色对应的权限
    public function loadDate($row){
        //赋值
        $this->name=$row->name;
        $this->description=$row->description;
        //查询角色对应的权限
        $powers=\Yii::$app->authManager->getPermissionsByRole($row->name);
        //将权限赋值到显示页面的数据中
        foreach ($powers as $power){
            $this->powers[]=$power->name;
        }
    }
}