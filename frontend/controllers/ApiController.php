<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use Symfony\Component\BrowserKit\Cookie;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller{
    public $enableCsrfValidation = false;
    public function init(){
        \Yii::$app->response->format=Response::FORMAT_JSON;
        parent::init();
    }
    //注册
    public function actionUser(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $ber=new Member();
            if(Member::findOne(['username'=>$ber->username])){
                return ['status'=>'-1','msg'=>'此名字已存在'];
            }
            if($request->post('username')==null && $request->post('password')==null){
                return ['status'=>'-1','msg'=>'用户名或密码不能为空'];
            }
            $ber->scenario = Member::SCENARIO_API_REGISTER;
            $ber->username=$request->post('username');
            $ber->password_hash=\Yii::$app->getSecurity()->generatePasswordHash($request->post('password'));
            $ber->email=$request->post('email');
            $ber->auth_key=\Yii::$app->getSecurity()->generateRandomString();
            $ber->created_at=time();
            $ber->tel=$request->post('tel');
            $ber->code=$request->post('code');
            if($ber->validate()){
                $ber->save();
                return ['status'=>'1','msg'=>'','data'=>$ber->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$ber->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请用post方式提交'];
    }
    //登陆
    public function actionLogin(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $log=Member::findOne(['username'=>$request->post('username')]);
            $log->scenario = Member::SCENARIO_API_REGISTER;
            if($log && \Yii::$app->security->validatePassword($request->post('password'),$log->password_hash)){
                $log->code=$request->post('code');
                $log->last_login_time=time();
                $log->last_login_ip=\Yii::$app->request->userIP;
                $log->save(false);
                \Yii::$app->user->login($log);
                return ['status'=>'1','msg'=>'登陆成功'];
            }
            return ['status'=>'-1','msg'=>'密码或用户名错误，请重新登陆'];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }
    //注销
    public function actionOut(){
        \Yii::$app->user->logout();
        return ['status'=>'1','msg'=>'注销成功'];
    }
    //获取当前登陆用户的信息
    public function actionLoginXin(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登陆'];
        }
        return ['status'=>'1','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }
    //修改密码
    public function actionAlterPas(){
        $request=new Request();
        if($request->isPost){
            $mem=Member::findOne(['id'=>\Yii::$app->user->id]);
            if(!\Yii::$app->security->validatePassword($request->post('old_password'),$mem->password_hash)){
                return ['status'=>'-1','msg'=>'原来的密码不正确'];
            }
            if($request->post('new_password1')!=$request->post('new_password')){
                return ['status'=>'-1','msg'=>'两次密码不一致'];
            }
            $mem->password_hash=\Yii::$app->getSecurity()->generatePasswordHash($request->post('new_password1'));
            $mem->save();
            return ['status'=>'1','msg'=>'','data'=>$mem->toArray()];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }

//    2.收货地址
//-添加地址
    public function actionAddSite(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $address=new Address();
            $address->name=$request->post('name');
            $address->province=$request->post('province');
            $address->city=$request->post('city');
            $address->area=$request->post('area');
            $address->detailed=$request->post('detailed');
            $address->tel=$request->post('tel');
            $address->default=$request->post('default');
            $address->member_id=\Yii::$app->user->id;
            if($address->validate()){
                $address->save();
                return ['status'=>'1','msg'=>'','data'=>$address->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$address->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请用post方式提交'];
    }
//-修改地址
    public function actionEditSite(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $address=Address::findOne(['id'=>$request->post('id')]);
            if(!$address){
                return ['status'=>'-1','msg'=>'您修改的地址不存在'];
            }
            $address->name=$request->post('name');
            $address->province=$request->post('province');
            $address->city=$request->post('city');
            $address->area=$request->post('area');
            $address->detailed=$request->post('detailed');
            $address->tel=$request->post('tel');
            $address->default=$request->post('default');
            $address->member_id=\Yii::$app->user->id;
            if($address->validate()){
                $address->save();
                return ['status'=>'1','msg'=>'','data'=>$address->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$address->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请用post方式提交'];
    }
//-删除地址
    public function actionDelSite(){
        if($id = \Yii::$app->request->get('id')){
            $address = Address::deleteAll(['id'=>$id]);
            if($address){
                return ['status'=>1,'msg'=>'删除成功'];
            }
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }
//-地址列表
    public function actionListSite(){
        if($member_id = \Yii::$app->request->get('member_id')){
            $address = Address::find()->where(['member_id'=>$member_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$address];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }
//3.商品分类
//-获取所有商品分类
    public function actionGoodsCate(){
        $cate=GoodsCategory::find()->orderBy('tree,lft')->all();
        return ['status'=>1,'mag'=>'','data'=>$cate];
    }
//-获取某分类的所有子分类???????
    public function actionCateSon(){
        if($cate_id=\Yii::$app->request->get('id')){
            $cate=[];
            $gen=GoodsCategory::find()->where(['id'=>$cate_id])->all();
            $er=GoodsCategory::find()->where(['parent_id'=>$gen->id])->all();
            $cate[]=$gen;
            $cate[]=$er;
            foreach($er as $san){
                $tree=GoodsCategory::find()->where(['parent_id'=>$san->id])->all();
            }
            $cate[]=$tree;
            return ['status'=>1,'mag'=>'','data'=>$cate];
        }
        return ['status'=>1,'mag'=>'参数不正确'];
    }
//-获取某分类的父分类
    public function actionCateFather(){
        if($cate_id=\Yii::$app->request->get('id')){
            $cha=GoodsCategory::findOne(['id'=>$cate_id]);
            $cate=GoodsCategory::find()->where(['id'=>$cha->parent_id])->all();
            return ['status'=>1,'mag'=>'','data'=>$cate];
        }
        return ['status'=>1,'mag'=>'参数不正确'];
    }
//4.商品
//-获取某分类下面的所有商品
    public function actionCateGoods(){
        if($cate_id=\Yii::$app->request->get('id')){
            //每页条数
            $par_page=\Yii::$app->request->get('par_page',2);
            //当前第几页
            $page=\Yii::$app->request->get('page',1);
            $page=$page<1?1:$page;
            $good=Goods::find();
            //总条数
            $total=$good->count(['goods_category_id'=>$cate_id]);
            $goods=$good->where(['goods_category_id'=>$cate_id])->offset($par_page*($page-1))->limit($par_page)->asArray()->all();
            return ['status'=>1,'mag'=>'','data'=>[
                'total'=>$total,
                'par_page'=>$par_page,
                'page'=>$page,
                'goods'=>$goods
            ]];
        }
        return ['status'=>1,'mag'=>'参数不正确'];
    }
//-获取某品牌下面的所有商品
    public function actionBrandGoods(){
        if($brand_id=\Yii::$app->request->get('id')){
            //每页条数
            $par_page=\Yii::$app->request->get('par_page',2);
            //当前第几页
            $page=\Yii::$app->request->get('page',1);
            $page=$page<1?1:$page;
            $good=Goods::find();
            //总条数
            $total=$good->count(['brand_id'=>$brand_id]);
            $goods=$good->where(['brand_id'=>$brand_id])->offset($par_page*($page-1))->limit($par_page)->asArray()->all();
            return ['status'=>1,'mag'=>'','data'=>[
                'total'=>$total,
                'par_page'=>$par_page,
                'page'=>$page,
                'goods'=>$goods
            ]];
        }
        return ['status'=>1,'mag'=>'参数不正确'];
    }
//5.文章
//-获取文章分类
    public function actionArtCate(){
        $cate=ArticleCategory::find()->orderBy('tree,lft')->all();
        return ['status'=>1,'mag'=>'','data'=>$cate];
    }
//-获取某分类下面的所有文章
    public function actionCateArt(){
        if($cate_id=\Yii::$app->request->get('id')){
            $article=Article::find()->where(['article_category_id'=>$cate_id])->all();
            return ['status'=>1,'mag'=>'','data'=>$article];
        }
        return ['status'=>1,'mag'=>'参数不正确'];
    }
//-获取某文章所属分类
    public function actionArtDeCate(){
        if($id=\Yii::$app->request->get('id')){
            $article=Article::find()->where(['id'=>$id])->all();
            $cate=ArticleCategory::find()->where(['id'=>$article->article_categoty_id])->all();
            return ['status'=>1,'mag'=>'','data'=>$cate];
        }
        return ['status'=>1,'mag'=>'参数不正确'];
    }
//6.购物车
//-添加商品到购物车
    public function actionAddFlow(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $goods_id=$request->post('goods_id');
            $amount=$request->post('amount');
            $goods=\backend\models\Goods::findOne(['id'=>$goods_id]);
            if($goods==null){
                return ['status'=>1,'mag'=>'商品不存在'];
            }


            if(\Yii::$app->user->isGuest){
                //未登录
                $cookies=$request->cookies;
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
                return ['status'=>1,'msg'=>'保存cookie成功'];
            }else{
                //登陆

//            $cart=new Cart();
                $cart=Cart::findOne(['goods_id'=>$goods_id]);
                if($cart){
                    $cart->amount=$amount+$cart->amount;
                    $cart->save();
                    return ['status'=>1,'msg'=>'购物车添加成功'];
                }else{
                    $cart=new Cart();
                    $cart->goods_id=$goods_id;
                    $cart->amount=$amount;
                    $cart->member_id=\Yii::$app->user->id;
                    $cart->save();
                    return ['status'=>1,'msg'=>'购物车添加成功'];
                }
        }


        }
        return ['status'=>'-1','msg'=>'请用post请求'];
    }
//-修改购物车某商品数量
    public function actionUpdateFlow(){
        if($amount=\Yii::$app->request->post('amount')){
            $goods_id=\Yii::$app->request->post('goods_id');
            $amount=\Yii::$app->request->post('amount');
            $goods=Goods::findOne(['id'=>$goods_id]);
            if($goods==null){
                return ['status'=>'-1','msg'=>'商品不存在'];
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
                return['status'=>'1','msg'=>'修改成功'];
            }else{
                //登陆
                $cart=Cart::findOne(['goods_id'=>$goods_id]);
                if($cart){
                    $cart->goods_id=$goods_id;
                    $cart->amount=$amount;
                    $cart->member_id=\Yii::$app->user->id;
                    $cart->save();
                }
            }
            return['status'=>'1','msg'=>'修改成功'];
        }
        return ['status'=>'-1','msg'=>'请提交正确的参数'];
    }
//-删除购物车某商品
    public function actionDelFlow(){
        if($goods_id=\Yii::$app->request->get('goods_id')){
            $cart=Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>\Yii::$app->user->id]);
            if(!$cart){
                return ['status'=>'-1','msg'=>'该商品购物车中没有'];
            }
            $cart->delete();
            return['status'=>'1','msg'=>'删除成功'];
        }
        return ['status'=>'-1','msg'=>'请提交正确的参数'];
    }
//-清空购物车
    public function actionEmpty(){
        if($member_id=\Yii::$app->user->id){
            Cart::deleteAll(['member_id'=>$member_id]);
            return['status'=>'-1','msg'=>'清除成功 '];
        }
        return ['status'=>'-1','msg'=>'请提交正确的参数'];
    }
//-获取购物车所有商品
    public function actionListFlow(){
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
            return ['status'=>'1','msg'=>'','data'=>$carts];
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
            return ['status'=>'1','msg'=>'','data'=>$carts];
        }
    }
//7.订单
//-获取支付方式
    public function actionPay(){
        if($id=\Yii::$app->request->get('id')){
            $pay=Order::$pay[$id];
            return ['status'=>'1','msg'=>'','datda'=>$pay];
        }
        return ['status'=>'1','msg'=>'请传正确的参数'];
    }
//-获取送货方式
    public function actionMode(){
        if($id=\Yii::$app->request->get('id')){
            $pay=Order::$mode[$id];
            return ['status'=>'1','msg'=>'','datda'=>$pay];
        }
        return ['status'=>'1','msg'=>'请传正确的参数'];
    }
//-提交订单
    public function actionAddOrder(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $rows=$request->post();
            //根据id查询到对应数据保存
            $address=Address::findOne(['id'=>$rows['address_id']]);
            if($address == null){
                return ['status'=>'-1','msg'=>'地址不存在'];
            }
            $order=new Order();

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
            $ploads=Cart::findAll(['member_id'=>\Yii::$app->user->id]);
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
                    return ['status'=>'-1','msg'=>'订单保存失败'];
                }
                //根据购物车数据，把商品的详情查询出来，保存到订单商品表
                $carts = Cart::findAll(['member_id'=>\Yii::$app->user->id]);
                foreach($carts as $cart){
                    $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                    //商品不存在
                    if($goods==null){
                        return ['status'=>'-1','msg'=>'商品已售完'];
                    }
                    //库存不足
                    if($goods->stock < $cart->amount){
                        return ['status'=>'-1','msg'=>'商品库存不足'];
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
                        return ['status'=>'-1','msg'=>'订单商品保存失败或商品库存更新失败'];
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
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }
//-获取当前用户订单列表
    public function actionListOrder(){
        $orders = Order::findOne(['member_id'=>\Yii::$app->user->id,'status'=>1]);
        if($orders==null){
            return ['status'=>'-1','msg'=>'对不起，你没有订单'];
        }
        foreach($orders as $order){
            $goods=OrderGoods::find()->where(['order_id'=>$order->id]);
        }
        return ['status'=>'1','msg'=>'','data'=>$goods];
    }
//-取消订单
    public function actionCancel(){
        $models = Order::find()->where(['status' => 1])->andWhere(['<', 'create_time', time() - 3600])->all();
        foreach ($models as $model) {
            $model->status = 0;
            $model->save();
            //将减少的商品数还回去
            $order_goods = OrderGoods::findAll(['order_id' => $model->id]);
            foreach ($order_goods as $goods) {
                Goods::updateAllCounters(['stock' => $goods->amount], 'id=' . $goods->goods_id);
            }
        }
    }
//8.高级api
//验证码
    public function action(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>3,
                'maxLength'=>3,
            ],
        ];
    }
//文件上传
    public function actionFile(){
        $img = UploadedFile::getInstanceByName('img');
        if($img){
            $fileName = '/upload/'.uniqid().'.'.$img->extension;
            $result = $img->saveAs(\Yii::getAlias('@webroot').$fileName,0);
            if($result){
                return ['status'=>'1','msg'=>'','data'=>$fileName];
            }
            return ['status'=>'-1','msg'=>$img->error];
        }
        return ['status'=>'-1','msg'=>'没有文件上传'];
    }
////手机发送验证码
    public function actionSms(){
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        $code=rand(10000,99999);
        $ret=\Yii::$app->sms->setNum($tel)->setContent(['code'=>$code])->send();
//        $ret=1;
        if($ret){
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            return ['status'=>'1','msg'=>''];
        }else{
            return ['status'=>'1','msg'=>'发送失败'];
        }
    }
}