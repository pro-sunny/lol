<div class="item_container">
    <div class="item_head">
        <div class="left item_image">
            <?
            echo CHtml::image(Utils::getItemImagePath($item_id));
            ?>
        </div>
        <div class="item_name">
            <? echo $name; ?>
        </div>
        <br>
        <div class="item_price">
            Price: <? echo $price; ?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="item_info">
        <?= $description?>
    </div>
</div>