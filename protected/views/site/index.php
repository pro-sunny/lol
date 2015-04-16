<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;


Yii::app()->CURL->options['timeout'] = 90;


// CVarDumper::dump($events, 10 ,1);

$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/item.json');
$data = CJSON::decode($string);
// $data = json_decode($string, true);
//echo '<pre>';
//print_r($data);
//echo '</pre>';

// echo 'xto vugrav';


//CVarDumper::dump($data, 10, 1);



$win_status = array( true => 'WIN', false => 'LOSE' );

if(Yii::app()->user->checkAccess('administrator')){
    echo "hello, I'm administrator";
}
if(Yii::app()->user->checkAccess('user')){
    echo "THE USER IS HERE!!!";
}

?>




