<?php

namespace frontend\controllers;


use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class MemberController extends \yii\web\Controller
{
    public $layout='login';
    //用户注册
    public function actionRegist(){
        $mould=new Member();
        $request=new Request();
        //判断是否提交了数据，并且验证数据
        if($mould->load($request->post()) && $mould->validate()){
            if(Member::findOne(['username'=>$mould->username])){
                throw new NotFoundHttpException('此名字已存在');
            }
            $mould->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($mould->password_hash);//密码加密
            $mould->auth_key = \Yii::$app->getSecurity()->generateRandomString();
            $mould->created_at=time();
            $mould->save(false);
            return $this->redirect(['member/index']);
        }
        return $this->render('regist',['mould'=>$mould]);
    }
    //用户登陆
    public function actionLogin(){
        $login=new LoginForm();
        $request=new Request();
        if($login->load($request->post()) && $login->validate()){
            $user=Member::findOne(['username'=>$login->username]);
            $user->last_login_time=time();
            $user->last_login_ip=\Yii::$app->request->userIP;
            $user->save(false);
            //登陆后合并cookie的数据
            $cart=new Cart();
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie){
                //有购物车信息就序列化
                $cart=unserialize($cookie->value);
                foreach($cart as $k=>$amount){
//                    $goods=Cart::find()->Where(['member_id'=>\Yii::$app->user->id])->andWhere(['goods_id'=>$k]);
//                    var_dump($k);exit;
                    $goods=Cart::findOne(['goods_id'=>$k]);
                    if($goods){
                        $goods->amount=$amount+$goods->amount;
                        $goods->save();
                    }else{
                        $goods=new Cart();
                        $goods->goods_id=$k;
                        $goods->amount=$amount;
                        $goods->member_id=\Yii::$app->user->id;
                        $goods->save();
                    }
                    \Yii::$app->response->cookies->remove('cart');
                }
            }
            return $this->redirect(['member/index']);
        }
        return $this->render('login',['login'=>$login]);
    }
    //用户注销
    public function actionOut(){
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    //发送手机验证码
    public function actionSendSms(){
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
            echo $code.'success';
        }else{
            echo '发送失败';
        }
    }
    //测试手机验证码
    public function actionSms(){


// 配置信息
        /*$config = [
            'app_key'    => '24488557',
            'app_secret' => '6f2377f60260733707fb9c6f41c55936',
             //'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];


// 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $code=rand(10000,99999);
        $req->setRecNum('13730686468')
            ->setSmsParam([
                'code' => $code
            ])
            ->setSmsFreeSignName('小明网站')
            ->setSmsTemplateCode('SMS_71945113');

        $resp = $client->execute($req);*/
        /*$code=rand(10000,99999);
        $ret=\Yii::$app->sms->setNum(13730686468)->setContent(['code'=>$code])->send();
        if($ret){
            echo $code.'发送成功';
        }else{
            echo '发送失败';
        }*/
    }
//发邮件
    public function actionMail(){
        \Yii::$app->malier->compose()
            ->setFrom('207227725@qq.com')//发件人
            ->setTo('207227725@qq.com')//收件人
            ->setSubject()//邮件主题
//            ->setTextBody('Plain text content')//邮件内容格式
            ->setHtmlBody()//邮件内容
            ->send();
    }
}
