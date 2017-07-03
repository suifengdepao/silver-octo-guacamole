<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsPhoto;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\NotFoundHttpException;

class GoodsController extends \yii\web\Controller
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
        $mould=Goods::find();
        //搜索
        if($keyword=\Yii::$app->request->get('keyword')){
            $mould->andWhere(['like','name',$keyword]);
        }
        if($sn=\Yii::$app->request->get('sn')){
            $mould->andWhere(['like','sn',$sn]);
        }
        if($price=\Yii::$app->request->get('price')){
            $mould->andWhere(['like','shop_price',$price]);
        }
        $page=new Pagination([
            'totalCount'=>$mould->count(),
            'pageSize'=>5,
        ]);
        $mould=$mould->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['mould'=>$mould,'page'=>$page]);
    }
    //添加数据
    public function actionAdd(){
        $mould=new Goods();
        $count=new GoodsDayCount();
        $intro=new GoodsIntro();
        $cate=ArrayHelper::map(GoodsCategory::find()->all(),'id','name');
        $brand=ArrayHelper::map(Brand::find()->all(),'id','name');
        $request=new Request();
//        var_dump($mould->validate());exit;
        if($mould->load($request->post()) && $mould->validate() && $intro->load($request->post())){
            $day=GoodsDayCount::findOne(['day'=>date('Y-m-d')]);
            if($day){
                $day->count=($day->count)+1;
                $day->save();
            }else{
                $count->day=date('Y-m-d');
                $count->count=1;
                $count->save();
            }
            $date=date('Ymd').str_pad(($day->count)+1,5,"0",STR_PAD_LEFT);//生成货号
            $mould->sn=$date;
            $mould->save();
            $id = $mould->attributes['id'];//商品表上一次添加数据的id
            $intro->goods_id=$id;
            $intro->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods/index']);
        }
        return $this->render('add',['mould'=>$mould,'cate'=>$cate,'brand'=>$brand,'intro'=>$intro]);
    }
    //修改商品数据
    public function actionEdit($id){
        $mould=Goods::findOne(['id'=>$id]);
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        $cate=ArrayHelper::map(GoodsCategory::find()->all(),'id','name');
        $brand=ArrayHelper::map(Brand::find()->all(),'id','name');
        $request=new Request();
        if($mould->load($request->post()) && $mould->validate()){
            $date=date('Ymd').str_pad($id,5,"0",STR_PAD_LEFT);//生成货号
            $mould->sn=$date;
            $mould->save(false);
            $intro->goods_id=$id;
            $intro->save(false);
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods/index']);
        }
        return $this->render('add',['mould'=>$mould,'cate'=>$cate,'brand'=>$brand,'intro'=>$intro]);
    }
    //删除商品
    public function actionDel($id){
        $mould=Goods::findOne(['id'=>$id]);
        $mould->status=0;
        $mould->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods/index']);
    }
    //详情
    public function actionContent($id){
        $mould=Goods::findOne(['id'=>$id]);
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('content',['intro'=>$intro,'mould'=>$mould]);
    }
    //图片墙
    public function actionPhoto($id){
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }

        return $this->render('photo',['goods'=>$goods]);
    }
    //图片删除
    public function actionDelph(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsPhoto::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $mould=new GoodsPhoto();
                    $mould->goods_id=\Yii::$app->request->post('goods_id');
                    $mould->logo=$action->getWebUrl();
                    $mould->save();
                    $action->output['fileUrl']=$mould->logo;
//                    $imgUrl=$action->getWebUrl();
//                    //调用七牛云组件，将图片上传到七牛
//                    $qiniu=\Yii::$app->qiniu;
//                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
//                    //获取图片在七牛云的地址
//                    $url=$qiniu->getLink($imgUrl);
//                    $action->output['fileUrl'] = $url;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
//                    "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/images/goods/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                     "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }
}
