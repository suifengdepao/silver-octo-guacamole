<?php
namespace backend\models;

use yii\base\model;

class LoginForm extends Model{
    public $username;//名称
    public $password_hash;//密码
    public $cookie;//自动登陆字段

    public function rules(){
        return [
            [['username','password_hash'],'required'],
            ['username','validateusername'],//自定义验证方法
            ['cookie','boolean'],
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'名称',
            'password_hash'=>'密码',
            'cookie'=>'自动登陆'
        ];
    }

    public function validateusername(){
        $account=User::findOne(['username'=>$this->username]);
        if($account){
            //用户存在 验证密码
            if(\Yii::$app->security->validatePassword($this->password_hash,$account->password_hash)){
                //账号密码正确，登录
                $cookie=$this->cookie?1*24*3600:0;
                \Yii::$app->user->login($account,$cookie);
            }else{
                $this->addError('password_hash','密码不正确');
            }
        }else{
            //账号不存在  验证错误
            $this->addError('username','账号不正确');
        }
    }
}