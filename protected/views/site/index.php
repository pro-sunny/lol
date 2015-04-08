<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;


Yii::app()->CURL->options['timeout'] = 90;

$base = 'https://na.api.pvp.net/api/lol/na/v2.2/match/1778704162?includeTimeline=true&api_key=';
// item image


// https://na.api.pvp.net/api/lol/na/v2.2/match/1778704162?api_key=7ab85dd4-4731-4422-b7d0-a9878e04dd7c

$url = $base.Yii::app()->params['key'];


$response = Yii::app()->CURL->run($url);

$data = CJSON::decode($response);

$summoners = $data['participants'];

$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/item.json');
$data = CJSON::decode($string);
// $data = json_decode($string, true);
echo '<pre>';
// print_r($data);
echo '</pre>';

echo 'xto vugrav';

echo '<br>';
echo '<br>';

//CVarDumper::dump($data, 10, 1);

echo '<br>';
echo '<br>';

$win_status = array( true => 'WIN', false => 'LOSE' );

$this->widget('EToolTipster', array(
    'target' => '.spell',
    'options' => array(
        'position'=>'top',
        'contentAsHTML'=>true,
        'speed'=>0,
        'functionInit'=>'js:function(origin) {
                var id = origin.attr("id");
                var data = id.split("_");
                $.ajax({
                    data: {champion_id:data[0], spell_id:data[1]},
                    type: "POST",
                    url: "'.$this->createUrl('site/getSpell').'",
                    success: function(data) {
                        origin.tooltipster("content", data);
                    }
                });
            }'
    )
));

$this->widget('EToolTipster', array(
    'target' => '.item',
    'options' => array(
        'position'=>'top',
        'contentAsHTML'=>true,
        'speed'=>0,
        'functionInit'=>'js:function(origin) {
                var id = origin.attr("id");
                $.ajax({
                    data: {id:id},
                    type: "POST",
                    url: "'.$this->createUrl('site/getItem').'",
                    success: function(data) {
                        origin.tooltipster("content", data);
                    }
                });
            }'
    )
));
?>
<table class="champions_data">
    <tr>
        <td>i1</td>
        <td>i2</td>
        <td>i3</td>
        <td>i4</td>
        <td>i5</td>
        <td>i6</td>
        <td>rank</td>
        <td>champion</td>
        <td>spells</td>
        <td>KDA</td>
        <td>Who won</td>
        <td>Domage done</td>
        <td>Domage taken</td>
    </tr>
    <? foreach ($summoners as $summoner) {
        $spells = Utils::getChampionSpells($summoner['championId']);
        ?>
        <tr>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item1']), '', array('class'=>'item', 'id'=>$summoner['stats']['item1']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item2']), '', array('class'=>'item', 'id'=>$summoner['stats']['item2']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item3']), '', array('class'=>'item', 'id'=>$summoner['stats']['item3']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item4']), '', array('class'=>'item', 'id'=>$summoner['stats']['item4']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item5']), '', array('class'=>'item', 'id'=>$summoner['stats']['item5']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item6']), '', array('class'=>'item', 'id'=>$summoner['stats']['item6']))?></td>
            <td><?= $summoner['highestAchievedSeasonTier']?></td>
            <td><?= CHtml::image(Utils::getChampionImage( $summoner['championId'] ))?></td>
            <td>
                <div class="left">
                    <?
                    $passive = $spells['passive']['image'];
                    echo CHtml::image('', '', array('class'=>'spell', 'id'=>$summoner['championId'].'_passive', 'style'=>'width: 48px; height: 48px; background: url(/images/dragon_data/sprite/'.$passive['sprite'].') -'.$passive['x'].'px -'.$passive['y'].'px;'))?><br>
                </div>
                <? for( $i = 0; $i < 4; $i++ ){?>
                    <div class="left">
                        <?= CHtml::image($spells[$i]['image'], '', array('class'=>'tooltip', 'id'=>$summoner['championId'].'_'.$i))?><br>
                    </div>
                <? } ?>
            </td>
            <td><?= $summoner['stats']['kills'].'/'.$summoner['stats']['deaths'].'/'.$summoner['stats']['assists']?></td>
            <td><?= $win_status[$summoner['stats']['winner']]?></td>
            <td><?= $summoner['stats']['totalDamageDealtToChampions']?></td>
            <td><?= $summoner['stats']['totalDamageTaken']?></td>
        </tr>
    <? } ?>
</table>