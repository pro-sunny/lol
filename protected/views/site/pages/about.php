<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
$step = 60 * 5;

$time = (double)Yii::app()->db->createCommand()->select('timestamp')->from('timestamp')->order('timestamp DESC')->limit(1)->queryScalar();

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


?>
<h1>About</h1>

<p>This is a "static" page. You may change the content of this page
by updating the file <code><?php echo __FILE__; ?></code>.</p>
