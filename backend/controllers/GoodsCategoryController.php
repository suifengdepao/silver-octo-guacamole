<?php
namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends Controller{
    public function actionIndex(){
        $gui=GoodsCategory::find()->orderBy('tree,lft')->all();
        return $this->render('index',['gui'=>$gui]);
    }
    public function actionAdd(){
       $mould=new GoodsCategory();
       $request=new Request();
       if($request->isPost){
           $mould->load($request->post());
           if($mould->validate()){
               //
               if($mould->parent_id){
                   //添加非一级分类
                   $parent=GoodsCategory::findOne(['id'=>$mould->parent_id]);
                   $mould->prependTo($parent);
               }else{
                   //添加一级分类
                   $mould->makeRoot();
               }
               \Yii::$app->session->setFlash('success','添加成功');
               return $this->redirect(['goods-category/index']);
           }
       }

        $cate=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
       return $this->render('add',['mould'=>$mould,'cate'=>$cate]);
    }
    public function actionEdit($id){
        $mould=GoodsCategory::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost) {
            $mould->load($request->post());
            if ($mould->validate()) {
                //
                if ($mould->parent_id) {
                    //添加非一级分类
                    $parent = GoodsCategory::findOne(['id' => $mould->parent_id]);
                    $mould->prependTo($parent);
                } else {
                    //添加一级分类
                    $mould->makeRoot();
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        $cate=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['mould'=>$mould,'cate'=>$cate]);
    }
}