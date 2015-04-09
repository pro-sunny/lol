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

$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/summoner.json');
$data = CJSON::decode($string);
// $data = json_decode($string, true);
echo '<pre>';
//print_r($data);
echo '</pre>';

echo 'xto vugrav';

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
        'content'=>'Loading...',
        'contentAsHTML'=>true,
        'speed'=>0,
        'functionBefore'=>'js:function(origin, continueTooltip) {
            continueTooltip();
            var id = origin.attr("id");

            if( origin.hasClass("item") ){
                if ( id != 0 ){
                    $.ajax({
                        data: {id:id},
                        type: "POST",
                        url: "'.$this->createUrl('site/getItem').'",
                        success: function(data) {
                            origin.tooltipster("content", data);
                        }
                    });
                }
            }

            if( origin.hasClass("spell") ){
                console.log(data);
                var data = id.split("_");
                if (data[0] == "0") {
                    $.ajax({
                        data: {spell_id:data[1]},
                        type: "POST",
                        url: "'.$this->createUrl('site/getSummonerSpell').'",
                        success: function(data) {
                            origin.tooltipster("content", data);
                        }
                    });
                } else {
                    $.ajax({
                        data: {champion_id:data[0], spell_id:data[1]},
                        type: "POST",
                        url: "'.$this->createUrl('site/getSpell').'",
                        success: function(data) {
                            origin.tooltipster("content", data);
                        }
                    });
                }

            }
        }'
    )
));
?>

<style>
    .champion{ width: 500px; }
    .icon{ width: 68px }
    .summoner_spells{ width: 30px }
    .summoner_spells img{ width:28px; padding: 2px }
    .kda{ width: 50px }
    .items{ width: 200px }
    .cs{ width: 30px }
    .gold{ width: 50px }
    .trinket{margin: 19px 0 0 2px;}
</style>

<div class="champions_data">
    <div class="left">
        <div class="champion">
            <div class="icon left">
                <img alt="" src="/images/dragon_data/champion/Ezreal.png" style="width: 64px">
            </div>
            <div class="summoner_spells left">
                <img class="tooltip spell" id="0_12" src="/images/dragon_data/spell/SummonerTeleport.png" alt=""> <br>
                <img class="tooltip spell" id="0_4" src="/images/dragon_data/spell/SummonerFlash.png" alt="">
            </div>
            <div class="kda left">
                10/10/10
            </div>
            <div class="items left">
                <div class="left">
                    <div class="first_row">
                        <img class="tooltip item tooltipstered" id="1037" src="/images/dragon_data/item/1037.png" alt="">
                        <img class="tooltip item tooltipstered" id="3078" src="/images/dragon_data/item/3078.png" alt="">
                        <img class="tooltip item tooltipstered" id="3117" src="/images/dragon_data/item/3117.png" alt="">
                    </div>
                    <div class="second_row">
                        <img class="tooltip item tooltipstered" id="3035" src="/images/dragon_data/item/3035.png" alt="">
                        <img class="tooltip item tooltipstered" id="3072" src="/images/dragon_data/item/3072.png" alt="">
                        <img class="tooltip item tooltipstered" id="1038" src="/images/dragon_data/item/1038.png" alt="">
                    </div>
                </div>

                <div class="trinket left">
                    <img class="tooltip item tooltipstered" id="3340" src="/images/dragon_data/item/3340.png" alt="">
                </div>
            </div>
            <div class="cs left"></div>
            <div class="gold left"></div>
        </div>
    </div>
    <div class="right">

    </div>
</div>



<table class="champions_data">
    <tr>
        <td>i0</td>
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
        $summonerSpell1 = Utils::getSummonerSpell( $summoner['spell1Id'] );
        $summonerSpell2 = Utils::getSummonerSpell( $summoner['spell2Id'] );
        ?>
        <tr>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item0']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item0']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item1']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item1']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item2']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item2']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item3']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item3']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item4']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item4']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item5']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item5']))?></td>
            <td><?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item6']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item6']))?></td>
            <td><?= $summoner['highestAchievedSeasonTier']?></td>
            <td><?= CHtml::image(Utils::getChampionImage( $summoner['championId'] ))?></td>
            <td>
                <div class="left">
                    <?
                    echo CHtml::image($summonerSpell1['image'], '', array('class'=>'tooltip spell', 'id'=>'0_'.$summoner['spell1Id'] ));
                    echo '<br>';
                    echo CHtml::image($summonerSpell2['image'], '', array('class'=>'tooltip spell', 'id'=>'0_'.$summoner['spell2Id'] ));

                    $passive = $spells['passive']['image'];
                    echo CHtml::tag('div', array('class'=>'tooltip spell', 'id'=>$summoner['championId'].'_passive', 'style'=>'width: 48px; height: 48px; background: url(/images/dragon_data/sprite/'.$passive['sprite'].') -'.$passive['x'].'px -'.$passive['y'].'px;'));
                    ?><br>
                </div>
                <? for( $i = 0; $i < 4; $i++ ){?>
                    <div class="left">
                        <?= CHtml::image($spells[$i]['image'], '', array('class'=>'tooltip spell', 'id'=>$summoner['championId'].'_'.$i))?><br>
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