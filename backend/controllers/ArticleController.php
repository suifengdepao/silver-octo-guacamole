<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller{
    public function actionIndex(){
        $article=Article::find()->all();
        return $this->render('index',['article'=>$article]);
    }
    public function actionAdd(){
        $article=new Article();
        $detail=new ArticleDetail();
        $cate=\backend\models\ArticleCategory::find()->all();
        $cates = ArrayHelper::map($cate,'id','name');
        $request=new Request();
        if($request->isPost){
            $article->load($request->post());
            $detail->load($request->post());
            if($article->validate()){
                $article->save(false);
                $detail->articel_id=$article->attributes['id'];
                $detail->save(false);
                \Yii::$app->session->setFlash('success','文章添加成功');
                return $this->redirect(['article/index']);
            }else{
                \Yii::$app->session->setFlash('danger','文章添加失败');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['article'=>$article,'detail'=>$detail,'cate'=>$cates]);
    }
    public function actionEdit($id){
        $article=Article::findOne(['id'=>$id]);
        $detail=ArticleDetail::findOne(['articel_id'=>$id]);
        $cate=\backend\models\ArticleCategory::find()->all();
        $cates = ArrayHelper::map($cate,'id','name');
        $request=new Request();
        if($request->isPost){
            $article->load($request->post());
            if($article->validate()){
                $article->save();
                $detail->articel_id=$article->attributes['id'];
                $detail->save(false);
                \Yii::$app->session->setFlash('success','文章修改成功');
                return $this->redirect(['article/index']);
            }else{
                \Yii::$app->session->setFlash('danger','文章修改失败');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['article'=>$article,'detail'=>$detail,'cate'=>$cates]);
    }
    public function actionDel($id){
        $mould=Article::findOne(['id'=>$id]);
        $mould->status=-1;
        $mould->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);
    }
    public function actionDeta($id){
        $deta=ArticleDetail::findOne(['articel_id'=>$id]);
        $wen=Article::findOne(['id'=>$id]);
        return $this->render('detail',['deta'=>$deta,'wen'=>$wen]);
    }
}