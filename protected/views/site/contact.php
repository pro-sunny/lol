<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);

$this->widget('EToolTipster', array(
    'target' => '.tooltip',
    'options' => array(
        'position'=>'right',
        'content'=>'Loading',
        'contentAsHTML'=>true,
        'speed'=>0,
        'fixedWidth'=>100,
        'functionBefore'=>'js:function(origin, continueTooltip) {
                continueTooltip();
                id = origin.attr("id");

                $.ajax({
                    data: {id:id},
                    type: "POST",
                    url: "getContent",
                    success: function(data) {
                        origin.tooltipster("content", data);
                    }
                });
            }'
    )
));

//  origin.tooltipster("content", html);


?>

<div style="display: none">
    <div class="contenterino2">
        <img alt="" src="/images/dragon_data/champion/Zed.png">
        <br>4New content has been loaded LOADED HAHAHA4
    </div>
    <div class="contenterino2">
        <img alt="" src="/images/dragon_data/champion/Zed.png">
        <br>pls CRY!!!
    </div>
</div>


<h1>Contact Us</h1>


<a href="http://www.yiiframework.com" class="tooltip" id="Zed">
    Some thing here
</a>
<br>
<br>
<a href="http://www.yiiframework.com" class="tooltip" id="Aatrox">
    Some thing there
</a>

<div>
    <img alt="" src="" style="width: 48px; height: 48px; background: url(/images/dragon_data/sprite/passive0.png) -48px 0;">
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