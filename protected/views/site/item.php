<div class="spell">
    <div class="spell_head">
        <div class="left spell_image">
            <?
            echo CHtml::image(Utils::getItemImagePath($item_id));
            echo $price;
            ?>
        </div>
    </div>
    <div class="spell_info">
        <?= $description?>
    </div>
</div>