<div class="spell_container">
    <div class="spell_head">
        <div class="left spell_image">
            <?
            echo CHtml::image(Utils::getItemImagePath($item_id));
            ?>
        </div>
        <? echo $name; ?><br>
        <? echo $price; ?>
    </div>
    <div class="clear"></div>
    <div class="spell_info">
        <?= $description?>
    </div>
</div>