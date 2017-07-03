<?php
namespace backend\controllers;

use backend\models\PowerForm;
use backend\models\RoleFOrm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\components\RbacFilter;

class RbacController extends Controller{
    //过滤器
    public function behaviors(){
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
    //权限增删改查
    //添加权限
    public function actionAddAuthority(){
        //实例化模型
        $authority=new PowerForm();
        //判断是否接收了数据，并且验证通过
        if($authority->load(\Yii::$app->request->post()) && $authority->validate()){
            //调用方法添加数据，并判断是否添加成功
            if($authority->addAuthority()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect(['rbac/authority-index']);
            }
        }
        //显示添加表单
        return $this->render('add-authority',['authority'=>$authority]);
    }
    //权限列表
    public function actionAuthorityIndex(){
        //获取数据
        $authority=\Yii::$app->authManager->getPermissions();

        //将数据放入页面
        return $this->render('authority-index',['authority'=>$authority]);
    }
    //修改权限
    public function actionEditAuthority($name){
        //根据name查询出对应数据
        $mould=\Yii::$app->authManager->getPermission($name);
        if($mould==null){
            throw new NotFoundHttpException('权限不存在');
        }
        //实例化修改表单
        $authority=new PowerForm();
        //将数据放入表单中
        $authority->name=$mould->name;
        $authority->description=$mould->description;
        //接受修改的数据，并验证
        if($authority->load(\Yii::$app->request->post()) && $authority->validate()){
            //调用方法修改数据,并判断是否成功
            if($authority->updateAuthority($name)){
                //修改成功，提醒，并跳转到列表页
                \Yii::$app->session->setFlash('succexx','修改权限成功');
                return $this->redirect(['rbac/authority-index']);
            }
        }
        //显示修改表单
        return $this->render('add-authority',['authority'=>$authority]);
    }
    //删除权限
    public function actionDelAuthority($name){
        //根据name查询数据
        $mould=\Yii::$app->authManager->getPermission($name);
        if($mould==null){
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($mould);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['rbac/authority-index']);
    }
    //角色增删改查
    //添加角色
    public function actionAddRole(){
        //实例化模型
        $role=new RoleForm();
        //判断是否接收到数据并且判断数据是否符合规则
        if($role->load(\Yii::$app->request->post()) && $role->validate()){
            //调用方法添加角色,并判断是否添加成功
            if($role->RoleAdd()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect(['role-index']);
            }
        }

        //显示角色添加页面
        return $this->render('add-role',['role'=>$role]);
    }
    //角色列表
    public function actionRoleIndex(){
        //获取数据
        $mould=\Yii::$app->authManager->getRoles();

        //将数据放入列表页
        return $this->render('role-index',['mould'=>$mould]);
    }
    //修改角色
    public function actionEditRole($name){
        //根据name查询出数据
        $row=\Yii::$app->authManager->getRole($name);
        if($row==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $role=new RoleForm();
        $role->loadDate($row);
        //判断是否接收到数据，并且验证通过
        if($role->load(\Yii::$app->request->post()) && $role->validate()){
            //使用方法修改数据，并判断是否成功
            if($role->updateRole($name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['role-index']);
            }
        }

        return $this->render('add-role',['role'=>$role]);
    }
    //删除角色
    public function actionDelRole($name){
        //根据name查询数据
        $mould=\Yii::$app->authManager->getRole($name);
        if($mould==null){
            throw new NotFoundHttpException('角色不存在');
        }
        \Yii::$app->authManager->remove($mould);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['rbac/role-index']);
    }
}