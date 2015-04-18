<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1 class="blue-text">Login</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
    <div class="row center" style="height: 80px; margin: 0 auto; width: 400px">
        <div class="input-field col">
            <i class="mdi-action-account-circle prefix blue-text"></i>
            <?php echo $form->textField($model,'username', array('class'=>'blue-text')); ?>
            <?php echo $form->labelEx($model,'username', array('class'=>'blue-text')); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>
    </div>
    <div class="row center" style="height: 80px; margin: 0 auto; width: 400px">
        <div class="input-field col">
            <i class="prefix blue-text"></i>
            <?php echo $form->passwordField($model,'password', array('class'=>'blue-text')); ?>
            <?php echo $form->labelEx($model,'password', array('class'=>'blue-text')); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
    </div>
    <br>
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
    <br>
    <br>
    <div class="col offset-s7 s5">
        <button class="btn waves-effect waves-light blue darken-1" type="submit" name="action">Login
            <i class="mdi-content-send right white-text"></i>
        </button>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<br>
<p>New user? <?= CHtml::link('Register', '/site/register')?></p>
<br>
<br>