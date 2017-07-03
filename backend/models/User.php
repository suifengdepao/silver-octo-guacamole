<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $tr_password;//确认密码字段
    public $code;//验证码
    public $role=[];//角色
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password_hash','tr_password'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'email'],
            [['password_reset_token'], 'unique'],
            ['tr_password','compare','compareAttribute'=>'password_hash'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['role','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password_hash' => '密码',
            'status' => '状态',
            'tr_password'=>'确认密码',
            'role'=>'角色',
        ];
    }
    /*//添加用户的方法
    public function adduser(){
//        var_dump($this->role);exit;
        if(User::findOne(['username'=>$this->username])){
            $this->addError('username','此名字已存在');
        }
        $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);//密码加密
        $this->auth_key = \Yii::$app->getSecurity()->generateRandomString();
        $this->created_at=time();
        $this->save();
        $userid=$this->attributes['id'];
        foreach($this->role as $role){
            $role=\Yii::$app->authManager->getRole($role);
            if($role) \Yii::$app->authManager->assign($role,$userid);
        }
    }*/
    //获取所有角色的方法
    public static function getroles(){
        $authmanager=\Yii::$app->authManager->getRoles();
        return ArrayHelper::map($authmanager,'name','description');
    }
    //静态方法创建时间
    public function behaviros(){
        return [
            'time'=>[
                'class'=>TimestampBehavior::className(),
                'attributes'=>[
                    self::EVENT_BEFORE_INSERT=>['created_at'],
                    self::EVENT_BEFORE_UPDATE=>['updated_at'],
                ]
            ]
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
