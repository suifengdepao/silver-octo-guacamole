<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $detailed
 * @property integer $tel
 * @property integer $default
 * @property integer $member_id
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province', 'city', 'area', 'detailed', 'tel'], 'required'],
            [['tel', 'default', 'member_id'], 'integer'],
            [['name', 'detailed'], 'string', 'max' => 255],
            [['province'], 'string', 'max' => 20],
            [['city'], 'string', 'max' => 10],
            [['area'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人:',
            'province' => '省',
            'city' => '市',
            'area' => '区',
            'detailed' => '详细地址:',
            'tel' => '电话:',
            'default' => '设为默认地址',
            'member_id' => '用户id',
        ];
    }
}
