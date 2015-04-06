<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?

Yii::app()->CURL->options['timeout'] = 90;

$base = 'https://na.api.pvp.net/api/lol/na/v2.2/match/1778704162?includeTimeline=true&api_key=';
// item image


// https://na.api.pvp.net/api/lol/na/v2.2/match/1778704162?api_key=7ab85dd4-4731-4422-b7d0-a9878e04dd7c

$url = $base.Yii::app()->params['key'];


$response = Yii::app()->CURL->run($url);

$data = CJSON::decode($response);

$summoners = $data['participants'];


echo '<br>';
echo '<br>';

//CVarDumper::dump($data, 10, 1);

echo '<br>';
echo '<br>';

/*
 * TODO: maybe later js resize
 *
function resize(img, w, h) {
  var canvas = document.createElement('canvas');
  canvas.width = w;
  canvas.height = h;
  canvas.getContext('2d').drawImage(img, 0, 0, w, h);
  return canvas;
}
 * */
$win_status = array( true => 'WIN', false => 'LOSE' );
?>
<table>
    <tr>
        <td>item1</td>
        <td>item2</td>
        <td>item3</td>
        <td>item4</td>
        <td>item5</td>
        <td>item6</td>
        <td>rank</td>
        <td>champion</td>
        <td>KDA</td>
        <td>Who won</td>
        <td>Domage done</td>
        <td>Domage taken</td>
    </tr>
    <? foreach ($summoners as $summoner) { ?>
        <tr>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item1']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item2']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item3']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item4']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item5']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item6']))?></td>
            <td><?= $summoner['highestAchievedSeasonTier']?></td>
            <td><?= CHtml::image(Utils::getChampionImage( $summoner['championId'] ))?></td>
            <td><?= $summoner['stats']['kills'].'/'.$summoner['stats']['deaths'].'/'.$summoner['stats']['assists']?></td>
            <td><?= $win_status[$summoner['stats']['winner']]?></td>
            <td><?= $summoner['stats']['totalDamageDealtToChampions']?></td>
            <td><?= $summoner['stats']['totalDamageTaken']?></td>
        </tr>
    <? } ?>
</table>