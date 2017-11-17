<?php
/**
 * @var $match_id string
 * @var $region string
 */

$baseUrl = $this->assetsBase;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl.'/js/match.js', CClientScript::POS_END);
$cs->registerCssFile($baseUrl.'/css/match.css');

$summoners = Yii::app()->db->createCommand()->select('participants')->from('match')->where('id=:id', array('id'=>$match_id))->queryScalar();
$summoners = CJSON::decode($summoners);

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

                    &nbsp;
                    Dealt - <?= $summoner['stats']['totalDamageDealtToChampions']?>&nbsp;<br>
                    Taken - <?= $summoner['stats']['totalDamageTaken']?>

                </div>
            </div>
        </div>
        <?
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