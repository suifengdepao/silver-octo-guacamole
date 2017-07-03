<?php
namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\web\Controller;
use yii\web\Request;
use backend\components\RbacFilter;

class ArticleCategoryController extends Controller{
    //过滤器
    public function behaviors(){
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
    public function actionIndex(){
        $category=ArticleCategory::find()->all();
        return $this->render('index',['category'=>$category]);
    }
    //添加文章分类
    public function actionAdd(){
        $category=new ArticleCategory();
        $request=new Request();
        if($request->isPost){
            //接收数据
            $category->load($request->post());
            //判断是否验证通过
            if($category->validate()){
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
        //根据id查询出对应的数据
        $category=ArticleCategory::findOne(['id'=>$id]);
        $request=new Request();
        //判断是否是post提交方式
        if($request->isPost){
            //接收数据
            $category->load($request->post());
            //判断数据是否通过验证规则
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
        //查询出对应id的数据
        $mould=ArticleCategory::findOne(['id'=>$id]);
        //将数据的状态变成删除
        $mould->status=-1;
        $mould->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article-category/index']);
    }
}