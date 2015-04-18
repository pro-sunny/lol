<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;

Yii::app()->CURL->options['timeout'] = 90;

$regions = Yii::app()->params['regions'];

$win_status = array( true => 'WIN', false => 'LOSE' );

if( !Yii::app()->user->isGuest ){
    if(Yii::app()->user->checkAccess('administrator')){
//        echo "hello, I'm administrator";
    }
    if(Yii::app()->user->checkAccess('user')){
//        echo "THE USER IS HERE!!!";
    }
}

?>