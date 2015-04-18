<?
$rank = Yii::app()->params['ranks'][$user['rank']];

if( !Yii::app()->user->isGuest ){ ?>
    <h1 class="header center orange-text ">URF Pride</h1>

    <div class="col s12 m3 champion" id="1">
        <div class="card card-avatar z-depth-2">
            <div class="left">
                <?= CHtml::image(Utils::getRankImagePath(strtoupper($rank).'_I'))?>
            </div>
            <div class="left" style="margin-top: 40px; margin-left: 30px;">
                <h2 class="text_b"><?= $user['login']?></h2>

            </div>
            <div class="right" style="margin: 30px 100px 0 0">
                <h5>Games: <?= $user['games']?></h5>
                <h5>Wins: <?= $user['wins']?></h5>
                <h5>Points: <?= $user['elo']?></h5>
            </div>
        </div>
    </div>
<? } ?>



<h3 class="header center orange-text "><?= ucfirst($rank).' ranked players'?></h3>


<table class="striped hoverable" >
<thead>
<tr>
    <th data-field="id">Position</th>
    <th data-field="id">Summoner</th>
    <th data-field="name">Favourite Champion</th>
    <th data-field="price">Wins</th>
    <th data-field="price">Points</th>
</tr>
</thead>

<tbody>
<?

$position = 1;
foreach ($players as $player) {
    $user_champion = Yii::app()->db->createCommand()->select('champion_id')->from('user_champion')->where('user_id=:user_id', array('user_id'=>$player['id']))->order('count DESC')->limit(1)->queryScalar();
    if( empty($user_champion) ){
        $champion_name = 'Tower';
    } else {
        $champion_name = Utils::$champions_data['keys'][$user_champion];
    }
    $class = '';
    if( $player['id'] == Yii::app()->user->id ){
        $class = 'class="yellow lighten-2"';
    }
    ?>
    <tr <?= $class?>>
        <td style="padding-left: 20px"><?= $position?></td>
        <td><?= $player['login']?></td>
        <td><?= $champion_name?></td>
        <td><?= $player['wins']?></td>
        <td><?= $player['elo']?></td>
    </tr>
    <? $position++;
} ?>
</tbody>
</table>

<div style="margin: 0 auto">
<?
$this->widget('CLinkPager', array(
    'currentPage'=>$pages->getCurrentPage(),
    'itemCount'=>$item_count,
    'pageSize'=>$page_size,
    'maxButtonCount'=>3,
    'header'=>'',
    'htmlOptions'=>array('class'=>'pagination', 'style'=>'margin: 0 auto; width: 400px'),
    'selectedPageCssClass'=>'active',
    'lastPageCssClass'=>'waves-effect',
    'prevPageLabel'=>'<',
    'nextPageLabel'=>'>',
    'firstPageLabel'=>'<<',
    'lastPageLabel'=>'>>'
));


?>
</div>

<br clear="all">
