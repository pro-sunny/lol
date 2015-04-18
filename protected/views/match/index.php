<?php
/**
 * @var $match_id string
 * @var $region string
 */

// $base = 'https://'.$region.'.api.pvp.net/api/lol/'.$region.'/v2.2/match/'.$match_id.'?includeTimeline=true&api_key=';

// $url = $base.Yii::app()->params['key'];

// $response = Yii::app()->CURL->run($url);

// $data = CJSON::decode($response);

//$time_start = microtime(true);
//$time_end = microtime(true);
//$execution_time = ($time_end - $time_start);



$summoners = Yii::app()->db->createCommand()->select('participants')->from('match')->where('id=:id', array('id'=>$match_id))->queryScalar();
$summoners = CJSON::decode($summoners);

// $summoners = $data['participants'];
// $timeline = $data['timeline'];


/*
$events = array();
foreach ($timeline['frames'] as $time) {
    if( !empty($time['events']) ){
        foreach ($time['events'] as $event) {
            $events[$event['eventType']] = $event['eventType'];
        }
    }
}
*/

$this->widget('EToolTipster', array(
    'target' => '.tooltip',
    'options' => array(
        'position'=>'top',
        'content'=>'Loading...',
        'contentAsHTML'=>true,
        'speed'=>0,
        'minWidth'=>200,
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
    .champion .icon img{ width: 96px; }
    .champion .icon { width: 96px; margin: 0 auto}
    .summoner_spells{ width: 30px }
    .summoner_spells img, .additional img{ width:28px; padding: 2px }
    .kda{ width: 50px }
    .items{ width: 200px }
    .champions_data .item {width: 36px;padding: 0; }
    .first_row{height: 36px; margin-bottom: 4px;}
    .cs{ width: 30px }
    .gold{ width: 50px }
    .trinket{margin: 19px 0 0 2px;}

    .champion.selected .card{
        margin-top: 5px;
        /*-webkit-box-shadow: 0 2px 25px 10px rgba(0, 0, 0, 0.16), 0 2px 20px 0 rgba(0, 0, 0, 0.12);
        box-shadow: 0 2px 25px 10px rgba(0, 0, 0, 0.16), 0 2px 20px 0 rgba(0, 0, 0, 0.12)*/
    }
    .champion .blue_team{background: #f5fafe}
    .champion .red_team{background: #fff3f3}
    .champion.disabled .card{ background: #cccccc; cursor: default }

    .level{
        position: absolute;
        left: 44px;
        top: 80px;
        color: #ffffff;
        font-weight: bold;
    }
</style>

<script>
    console.log(<?= $match_id?>);
    $(function(){
        $('.champion').click(function(){
            if ($(this).hasClass('disabled')) {
                return false;
            }
            $('.champion').each(function(){
                $(this).find('.card').removeClass('z-depth-4');
            });
            $('.champion.selected').removeClass('selected');

            $(this).addClass('selected');
            $(this).find('.card').addClass('z-depth-4');
        });

        $('.lock_in').click(function(){

            if( !$('.champion.selected').length ){
                return false;
            }

            var id = $('.champion.selected').attr('id');

            $('.champion').each(function(){
                if( !$(this).hasClass('selected') ){
                    $(this).addClass('disabled');
                }
            });

            $.post('match/checkAnswer', {id:id}, function(data){
                var counter = 4;
                var i = setInterval(function(){
                    $('.rank').html(counter - 1).show();
                    counter--;
                    if(counter === 0) {
                        clearInterval(i);

                        $('.lock_in').hide();
                        $('.next_fight').show();
                        $('.rank').html(data.rank);
                        $('.status_'+data.status).show();
                        $('.message').html('<em>'+data.message+'</em>');
                    }
                }, 1000);


            }, 'json');
            return false;
        })
    })
</script>


<div class="row">
    <?
    $i = 1;
    $class = 'left';
    $row_class = 'blue_team';

    foreach ($summoners as $summoner) {
        $spells = Utils::getChampionSpells($summoner['championId']);
        $summonerSpell1 = Utils::getSummonerSpell( $summoner['spell1Id'] );
        $summonerSpell2 = Utils::getSummonerSpell( $summoner['spell2Id'] );

        $item_ids = array(0=>$summoner['stats']['item0'], 1=>$summoner['stats']['item1'], 2=>$summoner['stats']['item2'], 3=>$summoner['stats']['item3'], 4=>$summoner['stats']['item4'], 5=>$summoner['stats']['item5'], )
        ?>


        <div class="col s12 m3 champion" id="<?= $summoner['participantId']?>">
            <div class="card card-avatar waves-effect waves-light <?= $row_class?>">
                <div class="icon" style="position: relative">
                    <?= CHtml::image(Utils::getChampionImage( $summoner['championId'] ), '', array('class'=>'activator'))?>
                    <div class="level"><?= $summoner['stats']['champLevel']?></div>
                </div>
                <div class="card-content" style="min-width: 135px">
                    <div>
                        <?
                        echo CHtml::image($spells['passive']['image'], '', array('class'=>'tooltip spell', 'id'=>$summoner['championId'].'_passive'));
                        for( $j = 0; $j < 4; $j++ ){?>
                            <?= CHtml::image($spells[$j]['image'], '', array('class'=>'tooltip spell', 'id'=>$summoner['championId'].'_'.$j))?>
                        <? } ?>
                    </div>

                    <?
                    echo CHtml::image($summonerSpell1['image'], '', array('class'=>'tooltip spell', 'id'=>'0_'.$summoner['spell1Id'] ));
                    echo CHtml::image($summonerSpell2['image'], '', array('class'=>'tooltip spell', 'id'=>'0_'.$summoner['spell2Id'] ));
                    ?>
                    <br>
                    <span class="card-title activator grey-text text-darken-4">
                        <em><?= $summoner['stats']['kills'].'/'.$summoner['stats']['deaths'].'/'.$summoner['stats']['assists']?><br/></em>
                    </span>
                    <p>
                        <?= CHtml::image(Utils::getItemImagePath($item_ids[0]), '', array('class'=>($item_ids[0]?'tooltip':'').' item', 'id'=>$item_ids[0]))?>
                        <?= CHtml::image(Utils::getItemImagePath($item_ids[1]), '', array('class'=>($item_ids[1]?'tooltip':'').' item', 'id'=>$item_ids[1]))?>
                        <?= CHtml::image(Utils::getItemImagePath($item_ids[2]), '', array('class'=>($item_ids[2]?'tooltip':'').' item', 'id'=>$item_ids[2]))?>
                        <br>
                        <?= CHtml::image(Utils::getItemImagePath($item_ids[3]), '', array('class'=>($item_ids[3]?'tooltip':'').' item', 'id'=>$item_ids[3]))?>
                        <?= CHtml::image(Utils::getItemImagePath($item_ids[4]), '', array('class'=>($item_ids[4]?'tooltip':'').' item', 'id'=>$item_ids[4]))?>
                        <?= CHtml::image(Utils::getItemImagePath($item_ids[5]), '', array('class'=>($item_ids[5]?'tooltip':'').' item', 'id'=>$item_ids[5]))?>
                    </p>
                    <? /*
                    &nbsp;<?= $summoner['stats']['totalDamageDealtToChampions']?>&nbsp;<br>
                    <?= $summoner['stats']['totalDamageTaken']?>
                    */ ?>

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
        if ($i == 5) {
            $row_class = 'red_team'; ?>
            <br clear="all">
            <div class="row">
                <div class="status_win" style="display:none;">
                    <h1 class="header center green-text accent-4">Victory</h1>
                </div>
                <div class="status_lose" style="display:none;">
                    <h1 class="header center red-text">Second Place</h1>
                </div>
                <p class="rank" style="font-weight: bold; margin: 0; height: 15px"></p>
                <h5 class="header col s12 light message"><?= $question?></h5>
                <a href="#" class="lock_in btn-large waves-effect waves-light amber darken-3">Lock In</a>
                <a class="next_fight btn-large waves-effect waves-light light-blue accent-3" href="/match" style="display: none">Fight</a>
            </div>
        <?
        }
        $i++;
    } ?>
</div>