<?php
namespace console\controllers;

use backend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\console\Controller;

class TaskController extends Controller{
    public function actionClean()
    {
        set_time_limit(0);//不限制脚本执行时间
        while (1){
            //超时未支付订单 状态变为取消
            $models = Order::find()->where(['status' => 1])->andWhere(['<', 'create_time', time() - 3600])->all();
            foreach ($models as $model) {
                $model->status = 0;
                $model->save();
                //将减少的商品数还回去
                $order_goods=OrderGoods::findAll(['order_id'=>$model->id]);
                foreach ($order_goods as $goods) {
                    Goods::updateAllCounters(['stock' => $goods->amount], 'id=' . $goods->goods_id);
                }
//                echo 'ID为'.$model->id.'的订单被取消了。。。';
            }
            //1秒钟执行一次
            sleep(1);
        }
    }
}