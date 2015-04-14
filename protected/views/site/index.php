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
$timeline = $data['timeline'];
$events = array();
foreach ($timeline['frames'] as $time) {
    if( !empty($time['events']) ){
        foreach ($time['events'] as $event) {
            $events[$event['eventType']] = $event['eventType'];
        }
    }
}

CVarDumper::dump($events, 10 ,1);

$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/championFull.json');
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

if(Yii::app()->user->checkAccess('administrator')){
    echo "hello, I'm administrator";
}


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
    .champion{ width: 500px; height: 130px; border: #ffffff 2px solid }
    .icon{ width: 80px }
    .icon img{width: 76px; padding: 2px}
    .summoner_spells{ width: 30px }
    .summoner_spells img, .additional img{ width:28px; padding: 2px }
    .kda{ width: 50px }
    .items{ width: 200px }
    .champions_data .item {width: 36px;padding: 0; }
    .first_row{height: 36px; margin-bottom: 4px;}
    .cs{ width: 30px }
    .gold{ width: 50px }
    .trinket{margin: 19px 0 0 2px;}

    .champion .main{ height: 80px}
    .champion .additional{ height: 40px}
    .champion.hover{ border: #FFD324 2px solid }

    .spell, .item { width: 30px; }
</style>

<div class="section scrollspy" id="team">
    <div class="container">
        <div class="row">
        <?
        $i = 1;
        $class = 'left';
        foreach ($summoners as $summoner) {
            $spells = Utils::getChampionSpells($summoner['championId']);
            $summonerSpell1 = Utils::getSummonerSpell( $summoner['spell1Id'] );
            $summonerSpell2 = Utils::getSummonerSpell( $summoner['spell2Id'] );
            ?>


                <div class="col s12 m3">
                    <div class="card card-avatar">
                        <div class="waves-effect waves-block waves-light">
                            <?= CHtml::image(Utils::getChampionImage( $summoner['championId'] ), '', array('class'=>'activator'))?>
                        </div>
                        <div class="card-content">
                            <div>
                                <?
                                echo CHtml::image($spells['passive']['image'], '', array('class'=>'tooltip spell', 'id'=>$summoner['championId'].'_passive'))
                                ?><br>
                            </div>

                            <div>
                            <? for( $j = 0; $j < 4; $j++ ){?>
                                <?= CHtml::image($spells[$j]['image'], '', array('class'=>'tooltip spell', 'id'=>$summoner['championId'].'_'.$j))?>
                            <? } ?>
                            </div>

                            <?
                            echo CHtml::image($summonerSpell1['image'], '', array('class'=>'tooltip spell', 'id'=>'0_'.$summoner['spell1Id'] ));
                            echo CHtml::image($summonerSpell2['image'], '', array('class'=>'tooltip spell', 'id'=>'0_'.$summoner['spell2Id'] ));
                            ?>
                            <br>
                            <span class="card-title activator grey-text text-darken-4">
                                <?= $summoner['stats']['kills'].'/'.$summoner['stats']['deaths'].'/'.$summoner['stats']['assists']?><br/>
                                <small><em><a class="red-text text-darken-1" href="#">Developer</a></em></small>
                            </span>
                            <p>
                                <a href="#">
                                    <?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item0']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item0']))?>
                                </a>
                                <a href="#">
                                    <?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item1']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item1']))?>
                                </a>
                                <a href="#">
                                    <?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item2']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item2']))?>
                                </a>
                                <a href="#">
                                    <?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item3']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item3']))?>
                                </a>
                                <a href="#">
                                    <?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item4']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item4']))?>
                                </a>
                                <a href="#">
                                    <?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item5']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item5']))?>
                                </a>
                                <a href="#">
                                    <?= CHtml::image(Utils::getItemImagePath($summoner['stats']['item6']), '', array('class'=>'tooltip item', 'id'=>$summoner['stats']['item6']))?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>




        <?


            /*
             *
             * <div class="champion">
                <div class="main">
                    <div class="cs <?= $class?>">
                        <?= $summoner['stats']['minionsKilled']?>
                    </div>
                    <div class="gold <?= $class?>">
                        <?= $summoner['stats']['goldEarned']?>
                    </div>
                    <div class="<?= $class?>">
                        <?= $summoner['highestAchievedSeasonTier']?>
                    </div>
                </div>
                <div class="additional">
                    <div class="<?= $class?>">
                        &nbsp;<?= $win_status[$summoner['stats']['winner']]?>&nbsp;
                    </div>
                    <div class="<?= $class?>">
                        &nbsp;<?= $summoner['stats']['totalDamageDealtToChampions']?>&nbsp;
                    </div>
                    <div class="<?= $class?>">
                        <?= $summoner['stats']['totalDamageTaken']?>
                    </div>
                </div>

            </div>
             *
             * */

            $i++;
        } ?>
    </div>
    </div>


</div>
