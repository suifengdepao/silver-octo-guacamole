<?php
namespace backend\widgets;

use backend\models\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;

class MenuWidget extends Widget{
    //widget实例化后执行的代码
    public function init(){
        parent::init();
    }
    //widget被调用后执行的 代码
    public function run(){
        NavBar::begin([
            'brandLabel' => '我的地盘我做主',
            'brandUrl' => \Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' =>['goods/index']],
        ];
        if (\Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登陆', 'url' =>\Yii::$app->user->loginUrl];
        } else {
            $menuItems[] = ['label'=> '注销 (' . \Yii::$app->user->identity->username . ')','url'=>['user/out']];
            //根据用户权限显示菜单
            //获取所有的一级菜单
            $menus=Menu::findAll(['parent_id'=>0]);
            foreach ($menus as $menu){
                $item=['label'=>$menu->name,'items'=>[]];
                foreach($menu->childs as $child){
                    //根据用户权限判断是否显示
                    if(\Yii::$app->user->can($child->url)){
                        $item['items'][]=['label'=>$child->name,'url'=>[$child->url]];
                    }
                }
                //如果没有子菜单，就不显示
                if(!empty($item['items'])){
                    $menuItems[]=$item;
                }
            }
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}