<div class="spell_container">
    <div class="spell_head">
        <div class="left spell_image">
            <? echo CHtml::image($spell['image']); ?>
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
    <br>
    <div class="spell_info">
        <?= $spell['tooltip']?>
    </div>
</div>