<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;;

class BrandController extends \yii\web\Controller
{
    //将数据查询出来显示到页面
    public function actionIndex()
    {
        //查询所有数据
        $mould=Brand::find()->all();
        //将数据放入列表页，并显示
        return $this->render('index',['mould'=>$mould]);
    }
    //添加品牌
    public function actionAdd(){
        //实例模型
        $mould=new Brand();
        //实例request方法
        $request=new Request();
        //判断是否是post提交方式
        if($request->isPost){
            //接受数据
            $mould->load($request->post());
            //验证前实例化文件上传对象
//            $mould->imgFile=UploadedFile::getInstance($mould,'logo');
            //判断是否提交数据成功
            if($mould->validate()){
                //判断是否上传了文件
                /*if($mould->imgFile){
                    //保存图片
                    $file='/images/brand/'.uniqid().'.'.$mould->imgFile->extension;
                    $mould->imgFile->saveAs(\Yii::getAlias('@webroot').$file,false);
                    //图片地址赋值到数据库字段中
                    $mould->logo=$file;
                }*/
                //把数据添加到数据库
                $mould->save();
                //添加成功后进行提示
                \Yii::$app->session->setFlash('success','品牌添加成功');
                //跳转到商品列表页
                return $this->redirect(['brand/index']);
            }
        }
        //显示添加页面
        return $this->render('add',['mould'=>$mould]);
    }
    //修改品牌
    public function actionEdit($id){
        //根据传来的id查询数据
        $mould=Brand::findOne(['id'=>$id]);
        $request=new Request();
        //判断是否是post提交方式
        if($request->isPost){
            //接受数据
            $mould->load($request->post());
            //验证数据之前实例化文件上传对象
//            $mould->imgFile=UploadedFile::getInstance($mould,'logo');
            //判断数据是否提交成功
            if($mould->validate()){
                //判断是否上传了文件
                /*if($mould->imgFile){
                    //保存图片
                    $file='/images/brand/'.uniqid().'.'.$mould->imgFile->extension;
                    $mould->imgFile->saveAs(\Yii::getAlias('@webroot').$file,false);
                    //赋值
                    $mould->logo=$file;
                }*/
                //将数据提交到数据库中，进行修改
                $mould->save();
                //修改成功进行提示
                \Yii::$app->session->setFlash('success','品牌修改成功');
                //跳转到品牌列表页
                return $this->redirect(['brand/index']);
            }else{
                //修改失败进行提示
                \Yii::$app->session->setFlash('danger','品牌修改失败');
                //跳转回品牌列表页
                return $this->redirect(['brand/index']);
            }
        }
        //显示修改页面
        return $this->render('add',['mould'=>$mould]);
    }
    //删除品牌
    public function actionDel($id){
        $mould=Brand::findOne(['id'=>$id]);
        $mould->status=-1;
        $mould->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['brand/index']);
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
                    $imgUrl=$action->getWebUrl();
                    //调用七牛云组件，将图片上传到七牛
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取图片在七牛云的地址
                    $url=$qiniu->getLink($imgUrl);
                    $action->output['fileUrl'] = $url;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    public function actionTest(){
        $ak = '9olCHcA2lk8kyl7DYh2qh4mPAVyQSpQ2sZCJR7ON';
        $sk = 'ZxslZPaHTAbgSCZ5z9aCcmCJod-eVyL0ZLI9sniv';
        $domain = 'http://or9sw8776.bkt.clouddn.com/';
        $bucket = 'yii2shop';

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        //要上传的文件
        $fileName=\Yii::getAlias('@webroot').'/upload/1.jpg';
        $key = '1.jpg';
        $r=$qiniu->uploadFile($fileName,$key);
        $url = $qiniu->getLink($key);
        var_dump($url);
    }
}
