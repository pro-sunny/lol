<div class="spell">
    <div class="spell_head">
        <div class="left spell_image">
            <? if($spell_type == 'passive') {
                if (!empty($summoner)) {
                    echo CHtml::image($spell['image']);
                } else {
                    $passive = $spell['image'];
                    echo CHtml::tag('div', array('style'=>'width: 48px; height: 48px; background: url(/images/dragon_data/sprite/'.$passive['sprite'].') -'.$passive['x'].'px -'.$passive['y'].'px;'));
                }
            } else {
                echo CHtml::image($spell['image']);
            }?>
            <br>
        </div>
        <div>
            <span class="spell_name"><?= $spell['name']?></span>
            <br />
            <? if($spell_type != 'passive'){ ?>
                Cooldown: <span class="number"><?= $spell['cooldown']?></span>
                <br />

                Cost: <span class="number"><?= $spell['cost']?></span>
                <br />

                Range: <span class="number"><?= $spell['range']?></span>
            <? } ?>
        </div>
    </div>
    <div class="spell_info">
        <?= $spell['tooltip']?>
    </div>
</div>