<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use backend\components\RbacFilter;

class UserController extends \yii\web\Controller
{
    /*//过滤器
    public function behaviors(){
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }*/
    public function actionIndex()
    {
//        var_dump(\Yii::$app->user->identity);exit;
        $user=User::find()->all();
        return $this->render('index',['user'=>$user]);
    }
    //添加管理员
    public function actionAdd(){
        $user=new User();//实例化模型
        $request=new Request();

        //判断是否提交了数据，并且验证数据
        if($user->load($request->post()) && $user->validate()){
            if(User::findOne(['username'=>$user->username])){
                throw new NotFoundHttpException('此名字已存在');
            }
            $user->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($user->password_hash);//密码加密
            $user->auth_key = \Yii::$app->getSecurity()->generateRandomString();
            $user->created_at=time();
            $user->save(false);
            $userid=$user->attributes['id'];
            foreach($user->role as $role){
                $role=\Yii::$app->authManager->getRole($role);
                if($role) \Yii::$app->authManager->assign($role,$userid);
            }
            //提示
            \Yii::$app->session->setFlash('success','用户添加成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['user'=>$user]);
    }
    //修改管理员
    public function actionEdit($id){
        $user=User::findOne(['id'=>$id]);
        $roles=\Yii::$app->authManager->getAssignments($id);
        foreach ($roles as $role){
            $user->role[]=$role->roleName;
        }

        $request=new Request();
        //判断是否提交了数据，并且验证数据
        if($user->load($request->post()) && $user->validate()){
            $user->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($user->password_hash);//密码加密
            $user->updated_at=time();
            $user->save(false);
            \Yii::$app->authManager->revokeAll($id);//删除管理员的角色
            foreach($user->role as $role){
                $role=\Yii::$app->authManager->getRole($role);
                if($role) \Yii::$app->authManager->assign($role,$id);
            }
            //提示
            \Yii::$app->session->setFlash('success','用户修改成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['user'=>$user]);
    }
    //删除管理员
    public function actionDel($id){
        User::deleteAll(['id'=>$id]);
        \Yii::$app->authManager->revokeAll($id);//删除管理员的角色
        return $this->redirect(['user/index']);
    }
    //登陆
    public function actionLogin(){
        $login=new LoginForm();
        $request=new Request();
        if($login->load($request->post()) && $login->validate()){
            $user=User::findOne(['username'=>$login->username]);
            $user->login_time=time();
            $user->login_ip=\Yii::$app->request->userIP;
            $user->save(false);
            \Yii::$app->session->setFlash('success','登陆成功');
            return $this->redirect(['goods/index']);
        }
        return $this->render('login',['login'=>$login]);
    }
    //注销
    public function actionOut(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['user/index']);
    }
    //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,//验证码最小长度
                'maxLength'=>4,//最大长度
            ],
        ];
    }
}
