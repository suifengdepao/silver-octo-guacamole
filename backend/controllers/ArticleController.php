<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use backend\components\RbacFilter;

class ArticleController extends Controller{
    //过滤器
    public function behaviors(){
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
    public function actionIndex(){
        //查询所有数据
        $article=Article::find()->all();
        return $this->render('index',['article'=>$article]);
    }
    public function actionAdd(){
        //实例化文章模型
        $article=new Article();
        //实例化文章详情模型
        $detail=new ArticleDetail();
        //查询出所有文章分类
        $cate=\backend\models\ArticleCategory::find()->all();
        //将文章分类变成可以在页面显示的数据
        $cates = ArrayHelper::map($cate,'id','name');
        $request=new Request();
        //判断是否是post提交方式
        if($request->isPost){
            //接收文章数据
            $article->load($request->post());
            //接受文章详情的数据
            $detail->load($request->post());
            //判断文章验证规则是否通过
            if($article->validate()){
                $article->save(false);
                //将刚添加数据的id赋值给文章详情表的文章id
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
        //查询id对应的数据
        $article=Article::findOne(['id'=>$id]);
        //查询id对应的文章详情
        $detail=ArticleDetail::findOne(['articel_id'=>$id]);
        //查询所有文章分类的数据
        $cate=\backend\models\ArticleCategory::find()->all();
        //将文章分类变成页面可以显示的数据
        $cates = ArrayHelper::map($cate,'id','name');
        $request=new Request();
        //判断是否是post提交方式
        if($request->isPost){
            //接收文章数据
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