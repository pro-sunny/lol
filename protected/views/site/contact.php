<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);


$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/map.json');
$data = CJSON::decode($string);

$forbidden_items = $data['data'][1]['UnpurchasableItemList'];

//additional items to remove
$forbidden_items[] = '2139';
$forbidden_items[] = '3084';
$forbidden_items[] = '3187';
$forbidden_items[] = '3104';
$forbidden_items[] = '3159';
$forbidden_items[] = '3181';
$forbidden_items[] = '3185';
$forbidden_items[] = '3504';
$forbidden_items[] = '3090';
$forbidden_items[] = '3170';
$forbidden_items[] = '3137';

$string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/item.json');
$data = CJSON::decode($string);

$tags = array();
$items_by_type = array();

$item_types = array(
    'defence'=>array('Armor' => 'Armor', 'Health' => 'Health', 'HealthRegen' => 'Health Regen', 'SpellBlock' => 'Magic Resist'),
    'attack'=>array('AttackSpeed'=>'Attack Speed', 'CriticalStrike'=>'Critical Strike', 'Damage'=>'Damage', 'LifeSteal'=>'Life Steal'),
    'magic'=>array('CooldownReduction'=>'Cooldown Reduction', 'Mana'=>'Mana', 'ManaRegen'=>'Mana Regen', 'SpellDamage'=>'Ability Power'),
    'movement'=>array('Boots'=>'Boots', 'NonbootsMovement'=>'Other Movement')
);

$types = array(
    'Armor'=>'defence', 'Health'=>'defence', 'HealthRegen'=>'defence', 'SpellBlock'=>'defence',
    'AttackSpeed'=>'attack', 'CriticalStrike'=>'attack', 'Damage'=>'attack', 'LifeSteal'=>'attack',
    'CooldownReduction'=>'magic', 'Mana'=>'magic', 'ManaRegen'=>'magic', 'SpellDamage'=>'magic',
    'Boots'=>'movement', 'NonbootsMovement'=>'movement',
);

$items = $data['data'];
foreach ($items as $id => $item) {
    if( in_array('Lane', $item['tags']) || in_array('Consumable', $item['tags']) ){
        continue;
    }
    foreach ($item['tags'] as $tag) {
        $count[$tag] = !empty($count[$tag]) ? ++$count[$tag] : 1;
        $tags[$tag] = $tag.' '.$count[$tag];
        if( empty($item['into']) && !empty($types[$tag]) && !in_array($id, $forbidden_items) ){
            $items_by_type[$types[$tag]][$id] = $item['gold']['total'];
        }
    }
}

?>

<h1>Contact Us</h1>


<div class="row">
    <div class="col s12">
        <ul class="tabs">
            <li class="tab col s3"><a class="active" href="#defence">Defence</a></li>
            <li class="tab col s3"><a href="#attack">Attack</a></li>
            <li class="tab col s3"><a href="#magic">Magic</a></li>
            <li class="tab col s3"><a href="#movement">Movement</a></li>
        </ul>
    </div>
    <br>
    <div class="items">
        <?
        foreach ($items_by_type as $type => $items) {
            arsort($items);
            echo '<div id="'.$type.'" class="col s12">';
            foreach ($items as $id => $item) {
                echo CHtml::image(Utils::getItemImagePath($id), '', array('class'=>'item', 'id'=>$id));
            }
            echo '</div>';
        }
        ?>
    </div>
</div>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success" title="some things here">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>