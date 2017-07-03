<?php

namespace frontend\controllers;

use chenkby\region\Region;
use frontend\models\Address;
use frontend\models\Locations;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class AddressController extends \yii\web\Controller
{
    public $layout='top';
    public function actionIndex()
    {
        $address=new Address();
        $id=\Yii::$app->user->id;
        $rows=Address::findAll(['member_id'=>$id]);
//        var_dump($id);exit;
        $request=new Request();

        if($address->load($request->post()) && $address->validate()){
            if($address->default=1){
                $rows=Address::find()->where(['member_id'=>$id])->andWhere(['default'=>1])->all();
                foreach ($rows as $row){
                    $row->default=0;
                    $row->save();
                }
            }
            $address->member_id=$id;
            $address->save();
            return $this->redirect(['address/index']);
        }
        return $this->render('address',['address'=>$address,'rows'=>$rows]);
    }
    //修改收货地址
    public function actionLocaEdit($id){
        $rows=Address::find()->all();
        $address=Address::findOne(['id'=>$id]);
        $request=new Request();
        if($address->load($request->post()) && $address->validate()){
            if($address->default=1){
                $rows=Address::find()->where(['member_id'=>\Yii::$app->user->id])->andWhere(['default'=>1])->all();
                foreach ($rows as $row){
                    $row->default=0;
                    $row->save();
                }
            }
            $address->member_id=\Yii::$app->user->id;
            $address->save(false);
            return $this->redirect(['address/index']);
        }

        return $this->render('address',['address'=>$address,'rows'=>$rows]);
    }
    //删除收获地址
    public function actionLocaDel($id){
        Address::deleteAll(['id'=>$id]);
        return $this->redirect(['address/index']);
    }
    //设为默认地址
    public function actionFit($id){
        $rows=Address::find()->where(['member_id'=>\Yii::$app->user->id])->andWhere(['default'=>1])->all();
        foreach ($rows as $row){
            $row->default=0;
            $row->save();
        }
        $cole=Address::findOne(['id'=>$id]);
        $cole->default=1;
        $cole->save();
        return $this->redirect(['address/index']);
    }
    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
            'model'=>Region::className()
        ];
        return $actions;
    }
}
