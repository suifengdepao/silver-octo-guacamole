<?php
namespace backend\components;

use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{
    public function beforeAction($action){
        $user=\Yii::$app->user;
        if(!$user->can($action->uniqueId)){
            //如果没有登陆
            if($user->isGuest){
                return $action->controller->redirect($user->loginUrl);
            }
            throw new HttpException(403,'你没有权限进行此操作');
            return false;
        }
        return parent::beforeAction($action);
    }
}