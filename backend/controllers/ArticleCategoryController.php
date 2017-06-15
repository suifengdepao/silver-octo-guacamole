<?php
namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\web\Controller;
use yii\web\Request;

class ArticleCategoryController extends Controller{
    public function actionIndex(){
        $category=ArticleCategory::find()->all();
        return $this->render('index',['category'=>$category]);
    }
    //添加文章分类
    public function actionAdd(){
        $category=new ArticleCategory();
        $request=new Request();
        if($request->isPost){
            $category->load($request->post());
            if($category->validate()){
//                var_dump($category);exit;
                $category->save();
                \Yii::$app->session->setFlash('success','文章分类添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                \Yii::$app->session->setFlash('danger','文章分类添加失败');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['category'=>$category]);
    }
    //修改文章分类
    public function actionEdit($id){
        $category=ArticleCategory::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $category->load($request->post());
            if($category->validate()){
                $category->save();
                \Yii::$app->session->setFlash('success','文章分类修改成功');
                return $this->redirect(['article-category/index']);
            }else{
                \Yii::$app->session->setFlash('danger','文章分类修改失败');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['category'=>$category]);
    }
    //删除文章分类
    public function actionDel($id){
        $mould=ArticleCategory::findOne(['id'=>$id]);
        $mould->status=-1;
        $mould->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article-category/index']);
    }
}