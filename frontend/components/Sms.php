<?php
namespace frontend\components;


use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use yii\base\Component;

class Sms extends Component{
    public $app_key;
    public $app_secret;
    public $sign_name;
    public $template_code;
    private $_num;
    private $_param=[];

    //设置手机号
    public function setNum($num){
        $this->_num=$num;
        return $this;
    }
    //设置短信内容
    public function setContent(array $param){
        $this->_param=$param;
        return $this;
    }
    //设置签名
    public function setSign($sign){
        $this->sign_name=$sign;
        return $this;
    }
    //设置短信模板
    public function setTem($id){
        $this->template_code=$id;
        return $this;
    }
    public function send(){
        $client = new Client(new App(['app_key'=>$this->app_key,'app_secret'=>$this->app_secret]));
        $req    = new AlibabaAliqinFcSmsNumSend;


        $req->setRecNum($this->_num)
            ->setSmsParam(
                $this->_param
            )
            ->setSmsFreeSignName($this->sign_name)
            ->setSmsTemplateCode($this->template_code);

        return $client->execute($req);
    }
}