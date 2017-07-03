<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Cart;
use backend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class OrderController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    public function actionTrue(){
        $this->layout='flow';
        return $this->render('true');
    }
    public function actionFff(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }
        $this->layout='flow';
        $order=new Order();
        $request=new Request();

        $ploads=Cart::findAll(['member_id'=>\Yii::$app->user->id]);
        $wares=[];
        foreach($ploads as $amount){
            $goods=Goods::findOne(['id'=>$amount['goods_id']])->attributes;
            $goods['amount']=$amount['amount'];
            $wares[]=$goods;
        }
        if($wares==null){
            \Yii::$app->getSession()->setFlash('error','请先添加商品到购物车');
            return $this->redirect(['goods/index']);
        }
        if($request->isPost){

                $rows=$request->post();
                //根据id查询到对应数据保存
                $address=Address::findOne(['id'=>$rows['address_id']]);
                if($address == null){
                    throw new NotFoundHttpException('地址不存在');
                }


                    $order->member_id = \Yii::$app->user->id;
                    $order->name = $address->name;
                    $order->province = $address->province;
                    $order->city = $address->city;
                    $order->area = $address->area;
                    $order->address = $address->detailed;
                    $order->tel = $address->tel;
                    $order->delivery_id = $rows['delivery_id'];
                    $order->delivery_name = Order::$mode[$rows['delivery_id']]['name'];
                    $order->delivery_price = Order::$mode[$rows['delivery_id']]['price'];
                    $order->payment_id = $rows['payment_id'];
                    $order->payment_name = Order::$pay[$rows['payment_id']]['name'];

                    //遍历商品计算价格
                    $tol = 0;
                    foreach ($ploads as $fei) {
                        $goods = Goods::findOne(['id' => $fei['goods_id']])->attributes;
                        $tol += $fei['amount'] * $goods['shop_price'];
                    }
                    $order->total = $tol;
                    $order->status = 1;
                    $order->create_time = time();
                //开启事务
                $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if(!$order->validate() ||  $order->save(false)) {
                        throw new Exception('订单保存失败！');
                    }
                    //根据购物车数据，把商品的详情查询出来，保存到订单商品表
                    $carts = Cart::findAll(['member_id'=>\Yii::$app->user->id]);
                    foreach($carts as $cart){
                        $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                        //商品不存在
                        if($goods==null){
                            throw new Exception('商品已售完');
                        }
                        //库存不足
                        if($goods->stock < $cart->amount){
                            throw new Exception('商品库存不足');
                        }
                        $order_goods = new OrderGoods();
                        $order_goods->order_id= $order->id;
                        $order_goods->goods_id= $goods->id;
                        $order_goods->goods_name= $goods->name;
                        $order_goods->logo= $goods->logo;
                        $order_goods->price= $goods->shop_price;
                        $order_goods->amount= $cart->amount;
                        $order_goods->total = $order_goods->price*$order_goods->amount;
                        //扣库存 //扣减该商品库存
                        $goods->stock -= $cart->amount;
                        if(!$order_goods->save() ||  !$goods->save()){
                            throw new Exception('订单商品保存失败或商品库存更新失败！');
                        }
                        Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
                        return $this->redirect(['order/true']);
                    }
                    //提交
                    $transaction->commit();
                }catch (Exception $e){
                    //回滚
                    $transaction->rollBack();
                }
            }
        $rows=Address::findAll(['member_id'=>\Yii::$app->user->id]);
        return $this->render('fff',['order'=>$order,'rows'=>$rows,'wares'=>$wares]);
    }
}
