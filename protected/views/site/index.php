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

$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/championFull.json');
$data = CJSON::decode($string);
// $data = json_decode($string, true);
echo '<pre>';
print_r($data);
echo '</pre>';


echo '<br>';
echo '<br>';

//CVarDumper::dump($data, 10, 1);

echo '<br>';
echo '<br>';

$win_status = array( true => 'WIN', false => 'LOSE' );

$this->widget('EToolTipster', array(
    'target' => '.tooltip',
    'options' => array(
        'position'=>'top',
        'content'=>'Loading',
        'contentAsHTML'=>true,
        'speed'=>0,
        'functionBefore'=>'js:function(origin, continueTooltip) {
                continueTooltip();
                var id = origin.attr("id");
                var data = id.split("_");

                console.log(data);

                $.ajax({
                    data: {champion_id:data[0], spell_id:data[1]},
                    type: "POST",
                    url: "site/getSpell",
                    success: function(data) {
                        origin.tooltipster("content", data);
                    }
                });
            }'
    )
));

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
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item1']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item2']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item3']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item4']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item5']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item6']))?></td>
            <td><?= $summoner['highestAchievedSeasonTier']?></td>
            <td><?= CHtml::image(Utils::getChampionImage( $summoner['championId'] ))?></td>
            <td>
                <div class="left">
                    <?= CHtml::image($spells['passive']['image']['full'], '', array('class'=>'tooltip', 'id'=>$summoner['championId'].'_passive'))?><br>
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