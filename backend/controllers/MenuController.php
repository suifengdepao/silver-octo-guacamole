<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;
use backend\components\RbacFilter;

class MenuController extends \yii\web\Controller
{
    //过滤器
    public function behaviors(){
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
    //显示数据
    public function actionIndex()
    {
        $menu=Menu::find()->all();
        return $this->render('index',['menu'=>$menu]);
    }
    //添加菜单
    public function actionAdd(){
        $menu=new Menu();

        if($menu->load(\Yii::$app->request->post()) && $menu->validate()){
            $menu->save(false);
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }

        $cate=ArrayHelper::map(Menu::find()->where(['parent_id'=>0])->all(),'id','name');
        $cate=ArrayHelper::merge([0=>'顶级分类'],$cate);
        return $this->render('add',['menu'=>$menu,'cate'=>$cate]);
    }
    //修改菜单
    public function actionEdit($id){
        $menu=Menu::findOne(['id'=>$id]);

        if($menu->load(\Yii::$app->request->post()) && $menu->validate()){
            $menu->save(false);
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['menu/index']);
        }
        $cate=ArrayHelper::map(Menu::find()->where(['parent_id'=>0])->all(),'id','name');
        $cate=ArrayHelper::merge([0=>'顶级分类'],$cate);
        return $this->render('add',['menu'=>$menu,'cate'=>$cate]);
    }
    //删除菜单
    public function actionDel($id){
        Menu::deleteAll(['id'=>$id]);
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['menu/index']);
    }
}
