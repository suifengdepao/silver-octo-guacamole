<?php
namespace frontend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use frontend\components\SphinxClient;
use frontend\models\Cart;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller{
    //主页
    public function actionIndex(){
        $this->layout='index';

        return $this->render('index');
    }
    //搜索功能
    public function actionTest(){
        $this->layout='top';
        $goods=Goods::find();
        if($keyword=\Yii::$app->request->get('keyword')){
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');//shopstore_search
            if(!isset($res['matches'])){
                $goods->where(['id'=>0]);
            }else{
                $ids=ArrayHelper::map($res['matches'],'id','id');
                $goods->where(['in','id',$ids]);
            }
            $pager = new Pagination([
                'totalCount'=>$goods->count(),
                'pageSize'=>5
            ]);

            $goods = $goods->limit($pager->limit)->offset($pager->offset)->all();
        }
//        var_dump($goods);exit;
        $brands=Brand::find()->all();
        return $this->render('list',['goods'=>$goods,'brands'=>$brands]);
    }
    //商品列表
    public function actionList($id){
        $this->layout='top';
        $goods=Goods::findAll(['goods_category_id'=>$id]);
        $brands=Brand::find()->all();
        return $this->render('list',['goods'=>$goods,'brands'=>$brands]);
    }
    //商品详情
    public function actionIntro($id){
        $this->layout='top';
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        $good=Goods::findOne(['id'=>$id]);
        $brand=\backend\models\Brand::findOne(['id'=>$good->brand_id]);
        return $this->render('intro',['intro'=>$intro,'good'=>$good,'brand'=>$brand]);
    }
    //添加到购物车
    public function actionAddFlow(){
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        $goods=Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }


        if(\Yii::$app->user->isGuest){
            //未登录
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){
                //没有购物车信息就赋值为空数组
                $cart=[];
            }else{
                //有购物车信息就序列化
                $cart=unserialize($cookie->value);
            }
            //将商品数和id放入cookie中
            $cookies=\Yii::$app->response->cookies;

            //判断购物车是否有商品，有就叠加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id]+=$amount;
            }else{
                $cart[$goods_id]=$amount;
            }
            $cookie=new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //登陆

//            $cart=new Cart();
            $cart=Cart::findOne(['goods_id'=>$goods_id]);
            if($cart){
                $cart->amount=$amount+$cart->amount;
                $cart->save();
            }else{
                $cart=new Cart();
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->member_id=\Yii::$app->user->id;
                $cart->save();
            }

        }
        return $this->redirect(['goods/flow']);
    }
    //购物车页面
    public function actionFlow(){
        $this->layout='flow';
        if(\Yii::$app->user->isGuest){
            //未登录
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){
                //没有购物车信息就赋值为空数组
                $cart=[];
            }else{
                //有购物车信息就序列化
                $cart=unserialize($cookie->value);
            }
            $carts=[];
            foreach ($cart as $id=>$amount){
                $goods=Goods::findOne(['id'=>$id])->attributes;
                $goods['amount']=$amount;
                $carts[]=$goods;
            }
        }else{
            //登陆
            $member_id=\Yii::$app->user->id;
            $carts=Cart::findAll(['member_id'=>$member_id]);
            if($carts==null){
                $cart=[];
            }else{
                $cart=$carts;
            }
            $carts=[];
            foreach($cart as $amount){
                $goods=Goods::findOne(['id'=>$amount['goods_id']])->attributes;
                $goods['amount']=$amount['amount'];
                $carts[]=$goods;
            }
        }
        return $this->render('flow',['carts'=>$carts]);
    }
    //商品数据修改
    public function actionUpdate(){
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        $goods=Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(\Yii::$app->user->isGuest){
            //未登录
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){
                //没有购物车信息就赋值为空数组
                $cart=[];
            }else{
                //有购物车信息就序列化
                $cart=unserialize($cookie->value);
            }
            //将商品数和id放入cookie中
            $cookies=\Yii::$app->response->cookies;

            //判断购物车是否有商品，有就叠加
            if($amount){
                $cart[$goods_id]=$amount;
            }else{
                if(key_exists($goods['id'],$cart))unset($cart[$goods_id]);
            }
            $cookie=new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //登陆
            $cart=Cart::findOne(['goods_id'=>$goods_id]);
            if($cart){
                if($amount==0){
                    $cart->delete();
                }
            }else{
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->member_id=\Yii::$app->user->id;
                $cart->save();
            }
        }
        return $this->redirect(['goods/flow']);
    }
}