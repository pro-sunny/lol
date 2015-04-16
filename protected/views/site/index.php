<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;

Yii::app()->CURL->options['timeout'] = 90;

//$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/item.json');
//$data = CJSON::decode($string);
//$data = json_decode($string, true);
//echo '<pre>';
//print_r($data);
//echo '</pre>';

// echo 'xto vugrav';

$regions = Yii::app()->params['regions'];
// $main_regions = Yii::app()->params['main_regions'];

// begin 1427865900
// end 1428918000
$time = 1427865900;

$time += 2 * 24 * 60 * 60;

echo date('Y-m-d H:i:s', $time);

$step = 60 * 5;

$time = (double)Yii::app()->db->createCommand()->select('timestamp')->from('timestamp')->order('timestamp DESC')->limit(1)->queryScalar();

$url = 'https://na.api.pvp.net/api/lol/na/v4.1/game/ids?beginDate=1427865900&api_key=';
//     https://euw.api.pvp.net/api/lol/euw/v4.1/game/ids?beginDate=1427865900&api_key=
$base = 'https://na.api.pvp.net/api/lol/na/v2.2/match/1778704162?includeTimeline=true&api_key=';

// https://na.api.pvp.net/api/lol/na/v2.2/match/1778704162?api_key=
$matches = array();

/*
for( $i = 0; $i < 10; $i++ ){
    $matches = array();
    $time += $step;
    foreach ($regions as $region) {
        $base = 'https://'.$region.'.api.pvp.net/api/lol/'.$region.'/v4.1/game/ids?beginDate='.$time.'&api_key=';
        $url = $base.Yii::app()->params['key'];
        $response = Yii::app()->CURL->run($url);
        $data = CJSON::decode($response);
        if (empty($data['status'])) {
            $matches[$region] = $data;
        }
    }
    Yii::app()->db->createCommand()->insert('timestamp', array('timestamp'=>$time, 'matches'=>CJSON::encode($matches)));
}
*/
/*

$timestamps = Yii::app()->db->createCommand()->from('timestamp')->limit(10, 10)->queryAll();
foreach ($timestamps as $row) {
    $data = CJSON::decode($row['matches']);
    foreach ($data as $region => $matches) {
        if (!empty($matches)) {
            foreach ($matches as $id) {
                Yii::app()->db->createCommand()->insert('match', array('id'=>$id, 'region'=>$region));
            }
        }
    }
}

*/
$matches = Yii::app()->db->createCommand()->from('match')->where('participants = ""')->limit(10)->queryAll();
foreach ($matches as $match) {
    // $base = 'https://'.$match['region'].'.api.pvp.net/api/lol/'.$match['region'].'/v4.1/game/ids?beginDate='.$match['id'].'&api_key=';
    $base = 'https://'.$match['region'].'.api.pvp.net/api/lol/'.$match['region'].'/v2.2/match/'.$match['id'].'?includeTimeline=true&api_key=';
    $url = $base.Yii::app()->params['key'];
    $response = Yii::app()->CURL->run($url);
    $data = CJSON::decode($response);
    if (!empty($data['participants'])) {
        Yii::app()->db->createCommand()->update('match', array('participants'=>CJSON::encode($data['participants'])), 'id=:id', array('id'=>$match['id']));
    }
}


//echo '<pre style="text-align: left">';
//print_r($matches[0]);
//echo '</pre>';

//var_dump($time) ;

echo '<pre style="text-align: left">';
print_r($matches);
echo '</pre>';

$win_status = array( true => 'WIN', false => 'LOSE' );

if(Yii::app()->user->checkAccess('administrator')){
    echo "hello, I'm administrator";
}
if(Yii::app()->user->checkAccess('user')){
    echo "THE USER IS HERE!!!";
}

?>




